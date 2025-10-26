<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\SeoMeta;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class SeoMetaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $seoEntries = SeoMeta::with('page')->orderBy('slug')->get();

        return view('admin.seo.index', compact('seoEntries'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.seo.create', [
            'seoMeta' => new SeoMeta(),
            'pages' => Page::orderBy('name')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);

        $seoMeta = SeoMeta::create($data);

        return redirect()->route('admin.seo.edit', $seoMeta)
            ->with('status', 'SEO entry created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function edit(SeoMeta $seoMeta): View
    {
        return view('admin.seo.edit', [
            'seoMeta' => $seoMeta,
            'pages' => Page::orderBy('name')->get(),
            'metaJson' => $seoMeta->meta ? json_encode($seoMeta->meta, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : null,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SeoMeta $seoMeta): RedirectResponse
    {
        $data = $this->validated($request, $seoMeta);

        $seoMeta->update($data);

        return redirect()->route('admin.seo.edit', $seoMeta)
            ->with('status', 'SEO entry updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SeoMeta $seoMeta): RedirectResponse
    {
        $seoMeta->delete();

        return redirect()->route('admin.seo.index')
            ->with('status', 'SEO entry deleted.');
    }

    /**
     * @return array<string, mixed>
     */
    protected function validated(Request $request, ?SeoMeta $seoMeta = null): array
    {
        $seoMetaId = $seoMeta?->id;

        $data = $request->validate([
            'page_id' => ['nullable', 'exists:pages,id'],
            'slug' => ['required', 'string', 'max:255', Rule::unique('seo_metas', 'slug')->ignore($seoMetaId)],
            'title' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'keywords' => ['nullable', 'string', 'max:255'],
            'canonical_url' => ['nullable', 'url'],
            'og_title' => ['nullable', 'string', 'max:255'],
            'og_description' => ['nullable', 'string'],
            'og_image' => ['nullable', 'string', 'max:255'],
            'twitter_card' => ['nullable', 'string', 'max:255'],
            'meta' => ['nullable', 'string'],
        ]);

        if (isset($data['meta']) && trim($data['meta']) !== '') {
            $decoded = json_decode($data['meta'], true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw ValidationException::withMessages([
                    'meta' => 'Meta must be valid JSON. Error: ' . json_last_error_msg(),
                ]);
            }

            $data['meta'] = $decoded;
        } else {
            $data['meta'] = null;
        }

        $data['slug'] = Str::slug($data['slug']);

        return $data;
    }
}
