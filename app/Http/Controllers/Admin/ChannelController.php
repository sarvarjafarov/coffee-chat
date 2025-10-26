<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Channel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ChannelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $channels = Channel::query()
            ->withCount('coffeeChats')
            ->orderBy('label')
            ->get();

        return view('admin.channels.index', compact('channels'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.channels.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);

        $channel = Channel::create($data);

        return redirect()->route('admin.channels.edit', $channel)
            ->with('status', 'Channel created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Channel $channel): RedirectResponse
    {
        return redirect()->route('admin.channels.edit', $channel);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Channel $channel): View
    {
        $channel->loadCount('coffeeChats');

        return view('admin.channels.edit', compact('channel'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Channel $channel): RedirectResponse
    {
        $data = $this->validatedData($request, $channel);

        $channel->update($data);

        return redirect()->route('admin.channels.edit', $channel)
            ->with('status', 'Channel updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Channel $channel): RedirectResponse
    {
        $channel->delete();

        return redirect()->route('admin.channels.index')
            ->with('status', 'Channel deleted.');
    }

    /**
     * @return array<string, mixed>
     */
    protected function validatedData(Request $request, ?Channel $channel = null): array
    {
        $channelId = $channel?->id;

        $data = $request->validate([
            'label' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:channels,slug,' . $channelId],
            'description' => ['nullable', 'string', 'max:255'],
        ]);

        $data['slug'] = $data['slug']
            ? Str::slug($data['slug'])
            : Str::slug($data['label']);

        return $data;
    }
}
