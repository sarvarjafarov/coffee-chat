<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Post;
use App\Models\SeoMeta;
use Illuminate\View\View;

class MarketingPageController extends Controller
{
    public function home(): View
    {
        return view('home', $this->pageData('home'));
    }

    public function insights(): View
    {
        return view('insights.index', $this->pageData('insights'));
    }

    public function stories(): View
    {
        $data = $this->pageData('stories');

        $posts = Post::query()
            ->where('is_published', true)
            ->orderByDesc('published_at')
            ->paginate(9);

        return view('stories.index', $data + ['posts' => $posts]);
    }

    public function mbaJobs(): View
    {
        $jobs = collect(config('mba_jobs.full_time'));
        $internships = collect(config('mba_jobs.internships'));

        return view('jobs.mba', [
            'jobs' => $jobs,
            'internships' => $internships,
            'seo' => [
                'title' => 'MBA Full-time Jobs · CoffeeChat OS',
                'description' => 'Curated full-time MBA opportunities with direct application links, company context, and role highlights.',
            ],
        ]);
    }

    protected function pageData(string $slug): array
    {
        $page = Page::with('components')->where('slug', $slug)->first();

        $components = $page?->components?->keyBy('key') ?? collect();
        $seo = SeoMeta::forSlug($slug);

        return [
            'page' => $page,
            'components' => $components,
            'seo' => $seo ? $seo->toArray() : [],
        ];
    }
}
