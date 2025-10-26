<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $pages = Page::withCount('components')
            ->orderBy('name')
            ->get();

        return view('admin.pages.index', compact('pages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.pages.create', ['page' => new Page()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);

        $page = Page::create($data);

        return redirect()->route('admin.pages.edit', $page)
            ->with('status', 'Page created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Page $page): RedirectResponse
    {
        return redirect()->route('admin.pages.edit', $page);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Page $page): View
    {
        $page->load('components');

        return view('admin.pages.edit', compact('page'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Page $page): RedirectResponse
    {
        $data = $this->validated($request, $page);

        $page->update($data);

        return redirect()->route('admin.pages.edit', $page)
            ->with('status', 'Page updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Page $page): RedirectResponse
    {
        $page->delete();

        return redirect()->route('admin.pages.index')
            ->with('status', 'Page deleted.');
    }

    /**
     * @return array<string, mixed>
     */
    protected function validated(Request $request, ?Page $page = null): array
    {
        $pageId = $page?->id;

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:pages,slug,' . $pageId],
            'description' => ['nullable', 'string', 'max:255'],
        ]);

        $data['slug'] = Str::slug($data['slug']);

        return $data;
    }
}
