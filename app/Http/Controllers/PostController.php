<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\SeoMeta;
use Illuminate\View\View;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function index(): View
    {
        $posts = Post::query()
            ->where('is_published', true)
            ->orderByDesc('published_at')
            ->paginate(10);

        $seo = optional(SeoMeta::forSlug('stories'))->toArray() ?? [];

        return view('posts.index', compact('posts', 'seo'));
    }

    public function show(Post $post): View
    {
        abort_unless($post->is_published, 404);

        $seo = [
            'title' => $post->title . ' — CoffeeChat OS',
            'description' => $post->excerpt ?? Str::limit(strip_tags($post->body), 160),
            'canonical_url' => url(route('posts.show', $post, absolute: false)),
            'og_title' => $post->title,
            'og_description' => $post->excerpt ?? Str::limit(strip_tags($post->body), 160),
        ];

        return view('posts.show', compact('post', 'seo'));
    }
}
