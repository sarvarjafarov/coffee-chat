<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FeedbackController extends Controller
{
    public function index(): View
    {
        $feedback = Feedback::query()->latest()->paginate(25);

        return view('admin.feedback.index', [
            'feedback' => $feedback,
        ]);
    }

    public function update(Request $request, Feedback $feedback)
    {
        $data = $request->validate([
            'status' => ['required', 'in:open,resolved'],
        ]);

        $feedback->update($data);

        return back()->with('status', 'Feedback updated.');
    }
}
