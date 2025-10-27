@php
    $themeSettings = collect($settings ?? []);
    $accentStart = old('accent_start', $themeSettings->get('accent_start', '#0ea5e9'));
    $accentEnd = old('accent_end', $themeSettings->get('accent_end', '#2563eb'));
    $surface = old('surface', $themeSettings->get('surface', '#f4fbff'));
    $surfaceAlt = old('surface_alt', $themeSettings->get('surface_alt', '#e6f6ff'));
    $textPrimary = old('text_primary', $themeSettings->get('text_primary', '#0f172a'));
    $textMuted = old('text_muted', $themeSettings->get('text_muted', '#475569'));
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-semibold text-gray-900">Marketing control centre</h2>
                <p class="text-sm text-gray-500">Adjust theme colours, manage page components, and keep your marketing site aligned.</p>
            </div>
            <a href="{{ route('admin.pages.index') }}" class="inline-flex items-center px-4 py-2 rounded-full bg-blue-600 text-white text-sm font-semibold shadow hover:bg-blue-500 transition">Open page manager</a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            @if (session('status'))
                <div class="rounded-2xl bg-blue-50 border border-blue-100 text-blue-800 px-4 py-3 text-sm">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="rounded-2xl bg-red-50 border border-red-100 text-red-800 px-4 py-3 text-sm">
                    <p class="font-semibold">Please fix the highlighted fields.</p>
                </div>
            @endif

            <div class="grid gap-6 lg:grid-cols-3">
                <section class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6 space-y-4">
                    <div>
                        <h3 class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Brand theme</h3>
                        <p class="text-sm text-slate-500 mt-1">Tune the colours used across buttons, gradients, and background accents.</p>
                    </div>
                    <form method="POST" action="{{ route('dashboard.theme') }}" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label class="flex items-center justify-between text-sm font-medium text-slate-600">Accent gradient start
                                    <span class="text-xs text-slate-400">{{ $accentStart }}</span>
                                </label>
                                <input type="color" name="accent_start" value="{{ $accentStart }}" class="w-full h-12 rounded-2xl border border-slate-200 cursor-pointer" />
                                @error('accent_start')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="flex items-center justify-between text-sm font-medium text-slate-600">Accent gradient end
                                    <span class="text-xs text-slate-400">{{ $accentEnd }}</span>
                                </label>
                                <input type="color" name="accent_end" value="{{ $accentEnd }}" class="w-full h-12 rounded-2xl border border-slate-200 cursor-pointer" />
                                @error('accent_end')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="flex items-center justify-between text-sm font-medium text-slate-600">Surface background
                                    <span class="text-xs text-slate-400">{{ $surface }}</span>
                                </label>
                                <input type="color" name="surface" value="{{ $surface }}" class="w-full h-12 rounded-2xl border border-slate-200 cursor-pointer" />
                                @error('surface')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="flex items-center justify-between text-sm font-medium text-slate-600">Surface accent
                                    <span class="text-xs text-slate-400">{{ $surfaceAlt }}</span>
                                </label>
                                <input type="color" name="surface_alt" value="{{ $surfaceAlt }}" class="w-full h-12 rounded-2xl border border-slate-200 cursor-pointer" />
                                @error('surface_alt')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="flex items-center justify-between text-sm font-medium text-slate-600">Primary text
                                        <span class="text-xs text-slate-400">{{ $textPrimary }}</span>
                                    </label>
                                    <input type="color" name="text_primary" value="{{ $textPrimary }}" class="w-full h-12 rounded-2xl border border-slate-200 cursor-pointer" />
                                    @error('text_primary')
                                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="flex items-center justify-between text-sm font-medium text-slate-600">Muted text
                                        <span class="text-xs text-slate-400">{{ $textMuted }}</span>
                                    </label>
                                    <input type="color" name="text_muted" value="{{ $textMuted }}" class="w-full h-12 rounded-2xl border border-slate-200 cursor-pointer" />
                                    @error('text_muted')
                                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-3 rounded-full bg-blue-600 text-white font-semibold shadow hover:bg-blue-500 transition">Update theme</button>
                    </form>
                </section>

                <section class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6 lg:col-span-2">
                    <h3 class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Site snapshot</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-4">
                        <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
                            <p class="text-xs uppercase tracking-wider text-slate-400">Marketing pages</p>
                            <p class="text-2xl font-semibold text-slate-900 mt-1">{{ $stats['pages'] }}</p>
                            <a href="{{ route('admin.pages.index') }}" class="text-sm text-blue-600 font-medium mt-3 inline-flex items-center gap-1">Manage pages <span class="mdi mdi-arrow-top-right"></span></a>
                        </div>
                        <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
                            <p class="text-xs uppercase tracking-wider text-slate-400">Page sections</p>
                            <p class="text-2xl font-semibold text-slate-900 mt-1">{{ $stats['components'] }}</p>
                            <a href="{{ route('admin.pages.index') }}#components" class="text-sm text-blue-600 font-medium mt-3 inline-flex items-center gap-1">Review components <span class="mdi mdi-arrow-top-right"></span></a>
                        </div>
                        <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
                            <p class="text-xs uppercase tracking-wider text-slate-400">Published posts</p>
                            <p class="text-2xl font-semibold text-slate-900 mt-1">{{ $stats['posts'] }}</p>
                            <a href="{{ route('admin.posts.index') }}" class="text-sm text-blue-600 font-medium mt-3 inline-flex items-center gap-1">Open blog manager <span class="mdi mdi-arrow-top-right"></span></a>
                        </div>
                    </div>
                </section>
            </div>

            <section class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6 space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-semibold text-slate-900">Pages &amp; sections</h3>
                        <p class="text-sm text-slate-500">Click a page or section to update copy, CTA labels, media, and styling.</p>
                    </div>
                    <a href="{{ route('admin.pages.create') }}" class="inline-flex items-center px-3 py-2 rounded-full border border-blue-200 text-blue-600 text-sm font-medium hover:bg-blue-50 transition">New page</a>
                </div>
                <div class="space-y-4">
                    @foreach($pages as $page)
                        <div class="rounded-2xl border border-slate-100 p-4">
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">
                                <div>
                                    <h4 class="text-lg font-semibold text-slate-900">{{ $page->name }}</h4>
                                    <p class="text-sm text-slate-500">Slug: <span class="font-mono text-slate-600">/{{ $page->slug }}</span> · {{ $page->components->count() }} components</p>
                                </div>
                                <a href="{{ route('admin.pages.edit', $page) }}" class="inline-flex items-center px-3 py-2 rounded-full bg-blue-600 text-white text-sm font-medium shadow hover:bg-blue-500 transition">Edit page</a>
                            </div>
                            @if($page->components->isNotEmpty())
                                <div class="flex flex-wrap gap-2 mt-3">
                                    @foreach($page->components->sortBy('position')->take(8) as $component)
                                        <a href="{{ route('admin.pages.components.edit', [$page, $component]) }}" class="inline-flex items-center px-3 py-1 rounded-full bg-blue-50 text-blue-600 text-sm font-medium">{{ $component->key }}</a>
                                    @endforeach
                                    @if($page->components->count() > 8)
                                        <span class="text-sm text-slate-500">+{{ $page->components->count() - 8 }} more…</span>
                                    @endif
                                </div>
                            @else
                                <p class="text-sm text-slate-400 mt-2">No components yet. <a href="{{ route('admin.pages.components.create', $page) }}" class="auth-link">Add one.</a></p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </section>

            <section class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                    <div>
                        <h3 class="text-base font-semibold text-slate-900">Latest stories &amp; posts</h3>
                        <p class="text-sm text-slate-500">Keep your narratives fresh. Publish customer stories or insight posts directly from the blog manager.</p>
                    </div>
                    <a href="{{ route('admin.posts.create') }}" class="inline-flex items-center px-3 py-2 rounded-full border border-blue-200 text-blue-600 text-sm font-medium hover:bg-blue-50 transition">Write a post</a>
                </div>
                <div class="grid gap-4 mt-4 sm:grid-cols-2 lg:grid-cols-4">
                    @forelse($recentPosts as $post)
                        <article class="rounded-2xl border border-slate-100 bg-slate-50 p-4 flex flex-col">
                            <span class="text-xs uppercase tracking-wider text-slate-400">{{ optional($post->published_at)->format('M d, Y') ?? 'Draft' }}</span>
                            <h4 class="text-sm font-semibold text-slate-900 mt-2 flex-grow">{{ $post->title }}</h4>
                            <span class="text-xs text-slate-500 mt-2">By {{ $post->author?->name ?? 'Unknown' }}</span>
                            <a href="{{ route('admin.posts.edit', $post) }}" class="text-sm text-blue-600 font-medium mt-3 inline-flex items-center gap-1">Edit post <span class="mdi mdi-arrow-top-right"></span></a>
                        </article>
                    @empty
                        <p class="text-sm text-slate-500">No posts yet. <a href="{{ route('admin.posts.create') }}" class="auth-link">Create your first story.</a></p>
                    @endforelse
                </div>
            </section>

            <section class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6">
                <h3 class="text-base font-semibold text-slate-900">Quick actions</h3>
                <div class="grid gap-3 mt-4 sm:grid-cols-2 lg:grid-cols-3">
                    <a href="{{ route('mba.jobs') }}" class="group border border-blue-100 rounded-2xl p-4 bg-blue-50/60 hover:bg-blue-100 transition">
                        <span class="text-sm font-semibold text-blue-700">Review MBA job board</span>
                        <p class="text-xs text-blue-600 mt-1">Check what the public sees, update copy via page components.</p>
                    </a>
                    <a href="{{ route('admin.seo.index') }}" class="group border border-slate-200 rounded-2xl p-4 hover:bg-slate-50 transition">
                        <span class="text-sm font-semibold text-slate-700">SEO metadata</span>
                        <p class="text-xs text-slate-500 mt-1">Update titles, descriptions, and share images.</p>
                    </a>
                    <a href="{{ route('admin.workspace-fields.index') }}" class="group border border-slate-200 rounded-2xl p-4 hover:bg-slate-50 transition">
                        <span class="text-sm font-semibold text-slate-700">Workspace fields</span>
                        <p class="text-xs text-slate-500 mt-1">Control additional coffee chat data captured by your team.</p>
                    </a>
                </div>
            </section>
        </div>
    </div>
</x-app-layout>
