<?php

namespace App\Http\Controllers\Workspace;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Schema;

class TeamFinderController extends Controller
{
    /**
     * Display the team finder page with optional results.
     */
    public function index(Request $request): View
    {
        $input = $request->validate([
            'position' => ['nullable', 'string', 'max:255'],
            'company' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'team_name' => ['nullable', 'string', 'max:255'],
        ]);

        $filters = collect($input)
            ->map(static function ($value) {
                return is_string($value) ? trim($value) : $value;
            })
            ->toArray();

        $normalized = collect($filters)
            ->map(static function ($value) {
                if (!is_string($value) || $value === '') {
                    return null;
                }

                return mb_strtolower($value, 'UTF-8');
            })
            ->toArray();

        $hasFilters = collect($filters)->filter(static fn ($value) => filled($value))->isNotEmpty();

        $contacts = null;

        $teamNameColumnAvailable = Schema::hasColumn('contacts', 'team_name');

        if ($hasFilters) {
            $contacts = Contact::query()
                ->with('company')
                ->when($normalized['position'], static function ($query, string $position): void {
                    $query->whereRaw('LOWER(position) LIKE ?', ["%{$position}%"]);
                })
                ->when($normalized['company'], static function ($query, string $companyName): void {
                    $query->whereHas('company', static function ($companyQuery) use ($companyName): void {
                        $companyQuery->whereRaw('LOWER(name) LIKE ?', ["%{$companyName}%"]);
                    });
                })
                ->when($normalized['city'], static function ($query, string $city): void {
                    $query->whereRaw('LOWER(location) LIKE ?', ["%{$city}%"]);
                })
                ->when($normalized['team_name'], static function ($query, string $teamName) use ($teamNameColumnAvailable): void {
                    if (! $teamNameColumnAvailable) {
                        return;
                    }

                    $query->whereRaw('LOWER(team_name) LIKE ?', ["%{$teamName}%"]);
                })
                ->orderBy('name')
                ->paginate(12)
                ->withQueryString();
        }

        return view('workspace.team-finder.index', [
            'filters' => $filters,
            'contacts' => $contacts,
            'hasFilters' => $hasFilters,
            'teamNameColumnAvailable' => $teamNameColumnAvailable,
        ]);
    }
}
