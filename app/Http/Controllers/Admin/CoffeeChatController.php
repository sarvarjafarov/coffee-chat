<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Channel;
use App\Models\CoffeeChat;
use App\Models\Company;
use App\Models\Contact;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CoffeeChatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $filters = [
            'status' => $request->string('status')->toString(),
            'company_id' => $request->integer('company_id'),
            'user_id' => $request->integer('user_id'),
            'search' => $request->string('search')->toString(),
        ];

        $coffeeChats = CoffeeChat::query()
            ->with(['company', 'contact', 'user', 'channels'])
            ->when($filters['status'], fn ($query, $status) => $query->where('status', $status))
            ->when($filters['company_id'], fn ($query, $companyId) => $query->where('company_id', $companyId))
            ->when($filters['user_id'], fn ($query, $userId) => $query->where('user_id', $userId))
            ->when($filters['search'], function ($query, string $search): void {
                $query->where(function ($inner) use ($search): void {
                    $inner->where('position_title', 'like', "%{$search}%")
                        ->orWhere('location', 'like', "%{$search}%")
                        ->orWhereHas('company', fn ($company) => $company->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('contact', fn ($contact) => $contact->where('name', 'like', "%{$search}%"));
                });
            })
            ->orderByDesc('scheduled_at')
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        $stats = [
            'total' => CoffeeChat::count(),
            'completed' => CoffeeChat::where('status', 'completed')->count(),
            'planned' => CoffeeChat::where('status', 'planned')->count(),
            'follow_up' => CoffeeChat::where('status', 'follow_up_required')->count(),
        ];

        return view('admin.coffee-chats.index', [
            'coffeeChats' => $coffeeChats,
            'filters' => $filters,
            'statusOptions' => $this->statusOptions(),
            'companies' => Company::orderBy('name')->get(),
            'users' => User::orderBy('name')->get(),
            'stats' => $stats,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.coffee-chats.create', [
            'coffeeChat' => new CoffeeChat([
                'status' => 'planned',
                'is_virtual' => true,
                'scheduled_at' => now()->addWeek(),
                'time_zone' => config('app.timezone'),
            ]),
            'statusOptions' => $this->statusOptions(),
            'companies' => Company::orderBy('name')->get(),
            'contacts' => Contact::with('company')->orderBy('name')->get(),
            'channels' => Channel::orderBy('label')->get(),
            'users' => User::orderBy('name')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);

        $coffeeChat = CoffeeChat::create($data);

        $coffeeChat->channels()->sync($request->input('channels', []));

        return redirect()->route('admin.coffee-chats.show', $coffeeChat)
            ->with('status', 'Coffee chat logged successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(CoffeeChat $coffeeChat): View
    {
        $coffeeChat->load([
            'company',
            'contact',
            'channels',
            'user',
            'followUpTasks' => fn ($query) => $query->orderBy('due_at'),
        ]);

        return view('admin.coffee-chats.show', [
            'coffeeChat' => $coffeeChat,
            'statusOptions' => $this->statusOptions(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CoffeeChat $coffeeChat): View
    {
        $coffeeChat->load(['channels', 'company', 'contact']);

        return view('admin.coffee-chats.edit', [
            'coffeeChat' => $coffeeChat,
            'statusOptions' => $this->statusOptions(),
            'companies' => Company::orderBy('name')->get(),
            'contacts' => Contact::with('company')->orderBy('name')->get(),
            'channels' => Channel::orderBy('label')->get(),
            'users' => User::orderBy('name')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CoffeeChat $coffeeChat): RedirectResponse
    {
        $data = $this->validatedData($request, $coffeeChat);

        $coffeeChat->update($data);

        $coffeeChat->channels()->sync($request->input('channels', []));

        return redirect()->route('admin.coffee-chats.show', $coffeeChat)
            ->with('status', 'Coffee chat updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CoffeeChat $coffeeChat): RedirectResponse
    {
        $coffeeChat->delete();

        return redirect()->route('admin.coffee-chats.index')
            ->with('status', 'Coffee chat removed.');
    }

    /**
     * @return array<string, mixed>
     */
    protected function validatedData(Request $request, ?CoffeeChat $coffeeChat = null): array
    {
        $statuses = array_keys($this->statusOptions());

        $data = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'company_id' => ['nullable', 'exists:companies,id'],
            'contact_id' => ['nullable', 'exists:contacts,id'],
            'position_title' => ['nullable', 'string', 'max:255'],
            'scheduled_at' => ['nullable', 'date'],
            'time_zone' => ['nullable', 'string', 'max:64'],
            'location' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'string', Rule::in($statuses)],
            'duration_minutes' => ['nullable', 'integer', 'between:5,480'],
            'is_virtual' => ['nullable', 'boolean'],
            'summary' => ['nullable', 'string'],
            'key_takeaways' => ['nullable', 'string'],
            'next_steps' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
            'rating' => ['nullable', 'integer', 'between:1,5'],
        ]);

        $data['is_virtual'] = $request->boolean('is_virtual');

        if (! empty($data['scheduled_at'])) {
            $data['scheduled_at'] = Carbon::parse($data['scheduled_at']);
        }

        if ($data['duration_minutes'] === null) {
            $data['duration_minutes'] = null;
        }

        if ($data['rating'] === null) {
            $data['rating'] = null;
        }

        return $data;
    }

    /**
     * @return array<string, string>
     */
    protected function statusOptions(): array
    {
        return [
            'planned' => 'Planned',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
            'follow_up_required' => 'Follow-up Required',
        ];
    }
}
