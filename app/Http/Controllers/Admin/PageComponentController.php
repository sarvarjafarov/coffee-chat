<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\PageComponent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PageComponentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Page $page): View
    {
        $components = $page->components()->orderBy('position')->get();

        return view('admin.pages.components.index', compact('page', 'components'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Page $page): View
    {
        return view('admin.pages.components.create', [
            'page' => $page,
            'component' => new PageComponent(['page_id' => $page->id]),
            'metaJson' => null,
            'styleJson' => null,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Page $page): RedirectResponse
    {
        $data = $this->validated($request, $page);

        $component = $page->components()->create($data);

        return redirect()->route('admin.pages.components.edit', [$page, $component])
            ->with('status', 'Component created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function edit(Page $page, PageComponent $component): View
    {
        $this->ensureRelation($page, $component);

        return view('admin.pages.components.edit', [
            'page' => $page,
            'component' => $component,
            'metaJson' => $component->meta ? json_encode($component->meta, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : null,
            'styleJson' => $component->style ? json_encode($component->style, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : null,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Page $page, PageComponent $component): RedirectResponse
    {
        $this->ensureRelation($page, $component);

        $data = $this->validated($request, $page, $component);

        $component->update($data);

        return redirect()->route('admin.pages.components.edit', [$page, $component])
            ->with('status', 'Component updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Page $page, PageComponent $component): RedirectResponse
    {
        $this->ensureRelation($page, $component);

        $component->delete();

        return redirect()->route('admin.pages.components.index', $page)
            ->with('status', 'Component deleted.');
    }

    /**
     * @return array<string, mixed>
     */
    protected function validated(Request $request, Page $page, ?PageComponent $component = null): array
    {
        $componentId = $component?->id;

        $data = $request->validate([
            'key' => [
                'required',
                'string',
                'max:255',
                Rule::unique('page_components', 'key')->where(fn ($query) => $query->where('page_id', $page->id))->ignore($componentId),
            ],
            'title' => ['nullable', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string'],
            'content' => ['nullable', 'string'],
            'media' => ['nullable', 'string'],
            'meta' => ['nullable'],
            'style' => ['nullable'],
            'position' => ['nullable', 'integer', 'min:0'],
        ]);

        unset($data['meta'], $data['style']);

        $data['meta'] = $this->resolveStructuredInput(
            $request->input('meta_json'),
            $request->input('meta'),
            'meta'
        );

        $data['position'] = $data['position'] ?? ($component?->position ?? 0);

        $data['style'] = $this->resolveStructuredInput(
            $request->input('style_json'),
            $request->input('style'),
            'style'
        );

        return $data;
    }

    protected function ensureRelation(Page $page, PageComponent $component): void
    {
        if ($component->page_id !== $page->id) {
            abort(404);
        }
    }

    protected function resolveStructuredInput(?string $json, $fallback, string $field): ?array
    {
        if (is_string($json) && trim($json) !== '') {
            $decoded = json_decode($json, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw ValidationException::withMessages([
                    "{$field}_json" => 'JSON is invalid: ' . json_last_error_msg(),
                ]);
            }

            $clean = $this->cleanArray($decoded);

            return $clean === [] ? null : $clean;
        }

        if (is_array($fallback)) {
            $clean = $this->cleanArray($fallback);

            return $clean === [] ? null : $clean;
        }

        if (is_string($fallback) && trim($fallback) !== '') {
            $decoded = json_decode($fallback, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw ValidationException::withMessages([
                    $field => 'JSON is invalid: ' . json_last_error_msg(),
                ]);
            }

            $clean = $this->cleanArray($decoded);

            return $clean === [] ? null : $clean;
        }

        return null;
    }

    protected function cleanArray(array $value): array
    {
        $clean = [];

        foreach ($value as $key => $item) {
            if (is_array($item)) {
                $nested = $this->cleanArray($item);

                if ($nested !== []) {
                    $clean[$key] = $nested;
                }

                continue;
            }

            if (is_string($item)) {
                $item = trim($item);
            }

            if ($item === '' || $item === null) {
                continue;
            }

            $clean[$key] = $item;
        }

        if ($clean === []) {
            return [];
        }

        if ($this->isSequentialArray($clean)) {
            $clean = array_values($clean);
        }

        return $clean;
    }

    protected function isSequentialArray(array $array): bool
    {
        $expected = 0;

        foreach ($array as $key => $value) {
            if ((string) $key !== (string) $expected) {
                return false;
            }

            $expected++;
        }

        return true;
    }
}
