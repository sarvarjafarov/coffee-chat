<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteMenuItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SiteMenuController extends Controller
{
    public function index(): View
    {
        $items = SiteMenuItem::orderBy('sort_order')->orderBy('label')->get();

        return view('admin.site-menu.index', [
            'items' => $items,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'label' => ['required', 'string', 'max:80'],
            'url' => ['required', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        SiteMenuItem::create([
            'label' => $data['label'],
            'url' => $data['url'],
            'sort_order' => $data['sort_order'] ?? 0,
        ]);

        return redirect()->route('admin.site-menu.index')->with('status', 'Menu link added.');
    }

    public function update(Request $request, SiteMenuItem $menuItem): RedirectResponse
    {
        $data = $request->validate([
            'label' => ['required', 'string', 'max:80'],
            'url' => ['required', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_visible' => ['nullable', 'boolean'],
        ]);

        $menuItem->update([
            'label' => $data['label'],
            'url' => $data['url'],
            'sort_order' => $data['sort_order'] ?? 0,
            'is_visible' => $request->boolean('is_visible'),
        ]);

        return redirect()->route('admin.site-menu.index')->with('status', 'Menu link updated.');
    }

    public function destroy(SiteMenuItem $menuItem): RedirectResponse
    {
        $menuItem->delete();

        return redirect()->route('admin.site-menu.index')->with('status', 'Menu link removed.');
    }
}
