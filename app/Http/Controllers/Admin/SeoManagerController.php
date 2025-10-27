<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SeoResourceUpdateRequest;
use App\Models\Page;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\View\View;
use RalphJSmit\Laravel\SEO\Models\SEO;

class SeoManagerController extends Controller
{
    /**
     * @var array<string, class-string>
     */
    protected array $resourceMap = [
        'pages' => Page::class,
        'posts' => Post::class,
    ];

    public function index(): View
    {
        $resources = collect($this->resourceMap)
            ->map(function (string $class, string $type): array {
                $items = $class::query()
                    ->when($type === 'posts', fn ($query) => $query->orderByDesc('published_at'))
                    ->when($type === 'pages', fn ($query) => $query->orderBy('name'))
                    ->with('seo')
                    ->get();

                return [
                    'type' => $type,
                    'label' => Str::headline(Str::singular($type)) . 's',
                    'items' => $items,
                ];
            })
            ->values();

        $orphanedSeo = SEO::query()
            ->doesntHave('model')
            ->orderByDesc('updated_at')
            ->get();

        return view('admin.seo.manager', compact('resources', 'orphanedSeo'));
    }

    public function edit(string $type, int $id): View
    {
        [$model, $type] = $this->resolveResource($type, $id);

        $seo = $model->seo;

        return view('admin.seo.edit-resource', [
            'resource' => $model,
            'type' => $type,
            'seo' => $seo,
        ]);
    }

    public function update(SeoResourceUpdateRequest $request, string $type, int $id): RedirectResponse
    {
        [$model, $type] = $this->resolveResource($type, $id);

        $data = Arr::only($request->validated(), [
            'title',
            'description',
            'author',
            'image',
            'canonical_url',
            'robots',
        ]);

        $schema = $request->input('schema');
        if ($schema !== null && trim($schema) !== '') {
            $decoded = json_decode($schema, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                return back()
                    ->withInput()
                    ->withErrors(['schema' => 'Schema must be valid JSON. Error: ' . json_last_error_msg()]);
            }

            $data['schema'] = $decoded;
        } else {
            $data['schema'] = null;
        }

        $model->seo()->updateOrCreate([], $data);

        return redirect()
            ->route('admin.seo.index')
            ->with('status', 'SEO settings updated for ' . $this->presentResourceLabel($type, $model));
    }

    /**
     * @return array{0: \Illuminate\Database\Eloquent\Model, 1: string}
     */
    protected function resolveResource(string $type, int $id): array
    {
        $typeKey = Str::plural(Str::lower($type));

        if (! array_key_exists($typeKey, $this->resourceMap)) {
            abort(404);
        }

        $class = $this->resourceMap[$typeKey];

        $model = $class::query()->with('seo')->findOrFail($id);

        return [$model, $typeKey];
    }

    protected function presentResourceLabel(string $type, $model): string
    {
        return match ($type) {
            'pages' => "page \"{$model->name}\"",
            'posts' => "post \"{$model->title}\"",
            default => 'resource',
        };
    }
}
