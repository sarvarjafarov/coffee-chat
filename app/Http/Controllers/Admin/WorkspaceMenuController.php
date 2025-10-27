<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WorkspaceMenuItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WorkspaceMenuController extends Controller
{
    public function index(): View
    {
        $items = WorkspaceMenuItem::whereNull('user_id')->orderBy('label')->paginate(25);

        return view('admin.menu.index', [
            'items' => $items,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'label' => ['required', 'string', 'max:80'],
            'url' => ['required', 'url', 'max:255'],
        ]);

        WorkspaceMenuItem::create($data + ['user_id' => null]);

        return redirect()->route('admin.menu.index')->with('status', 'Menu item added.');
    }

    public function destroy(WorkspaceMenuItem $menuItem): RedirectResponse
    {
        abort_unless($menuItem->user_id === null, 403);

        $menuItem->delete();

        return redirect()->route('admin.menu.index')->with('status', 'Menu item removed.');
    }
}
