<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\NetworkHealthAssessment;

class NetworkHealthController extends Controller
{
    public function show(): View
    {
        return view('network.health');
    }

    public function analyze(Request $request): View
    {
        $data = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'monthly_unique_contacts' => ['required', 'integer', 'between:0,200'],
            'warm_intros_last_quarter' => ['required', 'integer', 'between:0,50'],
            'average_follow_up_days' => ['required', 'numeric', 'between:0,30'],
            'industry_diversity' => ['required', 'integer', 'between:1,5'],
            'relationship_strength' => ['required', 'integer', 'between:1,5'],
        ]);

        $score = $this->score($data);
        $insights = $this->insights($score, $data);

        NetworkHealthAssessment::create([
            'email' => $data['email'],
            'monthly_unique_contacts' => $data['monthly_unique_contacts'],
            'warm_intros_last_quarter' => $data['warm_intros_last_quarter'],
            'average_follow_up_days' => $data['average_follow_up_days'],
            'industry_diversity' => $data['industry_diversity'],
            'relationship_strength' => $data['relationship_strength'],
            'score' => $score,
            'summary' => $insights['summary'],
            'recommendations' => $insights['messages'],
        ]);

        return view('network.health', [
            'score' => $score,
            'email' => $data['email'],
            'answers' => $data,
            'insights' => $insights,
        ]);
    }

    protected function score(array $data): int
    {
        $contactScore = min($data['monthly_unique_contacts'], 80) / 80 * 25;
        $introScore = min($data['warm_intros_last_quarter'], 20) / 20 * 20;
        $followUpScore = max(0, 12 - $data['average_follow_up_days']) / 12 * 20;
        $diversityScore = ($data['industry_diversity'] - 1) / 4 * 20;
        $strengthScore = ($data['relationship_strength'] - 1) / 4 * 15;

        $raw = $contactScore + $introScore + $followUpScore + $diversityScore + $strengthScore;

        return (int) round(max(0, min(100, $raw)));
    }

    protected function insights(int $score, array $data): array
    {
        $messages = [];

        if ($data['monthly_unique_contacts'] < 20) {
            $messages[] = 'Increase the number of unique check-ins each month to shrink your average path length toward the “six degrees” benchmark.';
        }

        if ($data['warm_intros_last_quarter'] < 5) {
            $messages[] = 'Warm introductions are your leverage. Aim for more shared conversations to keep the network graph dense.';
        }

        if ($data['average_follow_up_days'] > 7) {
            $messages[] = 'Faster follow-ups reinforce ties. Try to recap within a week so social distance stays low.';
        }

        if ($data['industry_diversity'] <= 2) {
            $messages[] = 'Broaden your industry mix. Cross-industry relationships shorten degrees of separation.';
        }

        if (empty($messages)) {
            $messages[] = 'Strong balance! Continue nurturing diverse, responsive relationships to keep your network resilient.';
        }

        return [
            'messages' => $messages,
            'summary' => $score >= 80
                ? 'Your network is operating at a “cluster coefficient” comparable to high-performing community builders.'
                : ($score >= 50
                    ? 'You have solid foundations. Target more warm intros and faster follow-ups to close the gap to elite network density.'
                    : 'Your graph looks sparse. Focus on reconnecting dormant ties and expanding into adjacent circles.'),
        ];
    }
}
