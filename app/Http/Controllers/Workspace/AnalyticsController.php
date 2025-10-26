<?php

namespace App\Http\Controllers\Workspace;

use App\Http\Controllers\Controller;
use App\Models\CoffeeChat;
use App\Models\WorkspaceField;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class AnalyticsController extends Controller
{
    public function index(): View
    {
        $chats = CoffeeChat::with('channels')
            ->where('user_id', auth()->id())
            ->get();

        $statusCounts = $chats->groupBy('status')->map->count();

        $channelCounts = [];
        foreach ($chats as $chat) {
            foreach ($chat->channels as $channel) {
                $channelCounts[$channel->label] = ($channelCounts[$channel->label] ?? 0) + 1;
            }
        }
        ksort($channelCounts);

        $dynamicFields = WorkspaceField::formFields()->where('in_analytics', true);
        $dynamicStats = [];

        foreach ($dynamicFields as $field) {
            $values = [];
            foreach ($chats as $chat) {
                $value = data_get($chat->extras, $field->key);

                if (is_array($value)) {
                    foreach ($value as $item) {
                        if ($item !== null && $item !== '') {
                            $values[] = $item;
                        }
                    }
                } elseif ($value !== null && $value !== '') {
                    $values[] = is_bool($value) ? ($value ? 'Yes' : 'No') : $value;
                }
            }

            $counts = collect($values)
                ->map(fn ($value) => (string) $value)
                ->countBy()
                ->sortDesc()
                ->take(8);

            $dynamicStats[] = [
                'field' => $field,
                'counts' => $counts,
            ];
        }

        return view('workspace.analytics.index', [
            'statusCounts' => $statusCounts,
            'channelCounts' => $channelCounts,
            'dynamicStats' => $dynamicStats,
            'totalChats' => $chats->count(),
            'completedChats' => $chats->where('status', 'completed')->count(),
        ]);
    }
}
