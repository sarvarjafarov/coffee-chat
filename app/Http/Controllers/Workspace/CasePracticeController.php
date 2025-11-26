<?php

namespace App\Http\Controllers\Workspace;

use App\Http\Controllers\Controller;
use App\Models\CaseSession;
use App\Models\CaseStudy;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CasePracticeController extends Controller
{
    public function index(): View
    {
        $sessions = CaseSession::with('caseStudy')
            ->where('user_id', auth()->id())
            ->orderByDesc('scheduled_at')
            ->orderByDesc('created_at')
            ->paginate(10);

        $allSessions = CaseSession::with('caseStudy')
            ->where('user_id', auth()->id())
            ->get();

        $statusCounts = $allSessions->groupBy('status')->map->count();
        $scoreAverages = $this->scoreAverages($allSessions);
        $overallScore = collect($scoreAverages)->filter()->avg();

        return view('workspace.cases.index', [
            'sessions' => $sessions,
            'statusOptions' => $this->statusOptions(),
            'scoreFields' => $this->scoreFields(),
            'totalSessions' => $allSessions->count(),
            'completedSessions' => $allSessions->where('status', 'completed')->count(),
            'plannedSessions' => $allSessions->where('status', 'planned')->count(),
            'scoreAverages' => $scoreAverages,
            'overallScore' => $overallScore ? round($overallScore, 1) : null,
            'statusCounts' => $statusCounts,
        ]);
    }

    public function create(): View
    {
        return view('workspace.cases.create', [
            'session' => new CaseSession([
                'status' => 'planned',
                'llm_feedback_opt_in' => true,
            ]),
            'caseStudies' => CaseStudy::where('is_active', true)->orderBy('title')->get(),
            'statusOptions' => $this->statusOptions(),
            'scoreFields' => $this->scoreFields(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);

        CaseSession::create([
            'user_id' => $request->user()->id,
            'case_study_id' => $data['case_study_id'] ?? null,
            'custom_title' => $data['custom_title'] ?? null,
            'status' => $data['status'],
            'scheduled_at' => $data['scheduled_at'] ?? null,
            'started_at' => $data['started_at'] ?? null,
            'completed_at' => $data['completed_at'] ?? null,
            'time_zone' => $data['time_zone'] ?? null,
            'duration_minutes' => $data['duration_minutes'] ?? null,
            'self_scores' => $data['self_scores'] ?? null,
            'reflection' => $data['reflection'] ?? null,
            'notes' => $data['notes'] ?? null,
            'llm_feedback_opt_in' => $request->boolean('llm_feedback_opt_in'),
            'llm_feedback' => $data['llm_feedback'] ?? null,
        ]);

        return redirect()->route('workspace.cases.index')
            ->with('status', 'Case session saved.');
    }

    public function edit(CaseSession $case): View
    {
        $this->authorizeSession($case);

        return view('workspace.cases.edit', [
            'session' => $case,
            'caseStudies' => CaseStudy::where('is_active', true)->orderBy('title')->get(),
            'statusOptions' => $this->statusOptions(),
            'scoreFields' => $this->scoreFields(),
        ]);
    }

    public function update(Request $request, CaseSession $case): RedirectResponse
    {
        $this->authorizeSession($case);

        $data = $this->validated($request);

        $case->update([
            'case_study_id' => $data['case_study_id'] ?? null,
            'custom_title' => $data['custom_title'] ?? null,
            'status' => $data['status'],
            'scheduled_at' => $data['scheduled_at'] ?? null,
            'started_at' => $data['started_at'] ?? null,
            'completed_at' => $data['completed_at'] ?? null,
            'time_zone' => $data['time_zone'] ?? null,
            'duration_minutes' => $data['duration_minutes'] ?? null,
            'self_scores' => $data['self_scores'] ?? null,
            'reflection' => $data['reflection'] ?? null,
            'notes' => $data['notes'] ?? null,
            'llm_feedback_opt_in' => $request->boolean('llm_feedback_opt_in'),
            'llm_feedback' => $data['llm_feedback'] ?? null,
        ]);

        return redirect()->route('workspace.cases.index')
            ->with('status', 'Case session updated.');
    }

    public function destroy(CaseSession $case): RedirectResponse
    {
        $this->authorizeSession($case);

        $case->delete();

        return redirect()->route('workspace.cases.index')
            ->with('status', 'Case session removed.');
    }

    protected function validated(Request $request): array
    {
        $statusOptions = array_keys($this->statusOptions());
        $scoreKeys = array_keys($this->scoreFields());

        $rules = [
            'case_study_id' => ['nullable', 'exists:case_studies,id'],
            'custom_title' => ['nullable', 'string', 'max:255'],
            'status' => ['required', Rule::in($statusOptions)],
            'scheduled_at' => ['nullable', 'date'],
            'started_at' => ['nullable', 'date'],
            'completed_at' => ['nullable', 'date'],
            'time_zone' => ['nullable', 'string', 'max:64'],
            'duration_minutes' => ['nullable', 'integer', 'between:5,360'],
            'reflection' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
            'llm_feedback' => ['nullable', 'string'],
            'llm_feedback_opt_in' => ['sometimes', 'boolean'],
        ];

        foreach ($scoreKeys as $key) {
            $rules["scores.$key"] = ['nullable', 'integer', 'between:1,5'];
        }

        $data = $request->validate($rules);
        $scores = collect($this->scoreFields())
            ->mapWithKeys(function ($_label, $key) use ($data) {
                $value = data_get($data, "scores.$key");
                return $value !== null ? [$key => (int) $value] : [];
            })
            ->filter();

        $data['self_scores'] = $scores->isNotEmpty() ? $scores->all() : null;

        return $data;
    }

    /**
     * @return array<string, string>
     */
    protected function statusOptions(): array
    {
        return [
            'planned' => 'Planned',
            'in_progress' => 'In progress',
            'completed' => 'Completed',
            'abandoned' => 'Abandoned',
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function scoreFields(): array
    {
        return [
            'structure' => 'Structure',
            'quant' => 'Math',
            'insight' => 'Insight',
            'communication' => 'Communication',
        ];
    }

    protected function scoreAverages($sessions): array
    {
        $scores = [];
        foreach ($this->scoreFields() as $key => $label) {
            $values = $sessions->map(function (CaseSession $session) use ($key) {
                return data_get($session->self_scores, $key);
            })->filter();

            $scores[$key] = $values->isNotEmpty()
                ? round($values->avg(), 1)
                : null;
        }

        return $scores;
    }

    protected function authorizeSession(CaseSession $case): void
    {
        if ($case->user_id !== auth()->id()) {
            abort(403);
        }
    }
}
