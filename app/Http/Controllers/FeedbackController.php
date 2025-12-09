<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'category' => ['nullable', 'string', 'max:100'],
            'message' => ['required', 'string', 'max:5000'],
            'page_path' => ['nullable', 'string', 'max:255'],
            'page_title' => ['nullable', 'string', 'max:255'],
            'session_id' => ['nullable', 'string', 'max:150'],
            'email' => ['nullable', 'email', 'max:255'],
            'name' => ['nullable', 'string', 'max:255'],
            'context' => ['nullable', 'array'],
        ]);

        $data['user_id'] = Auth::id();
        $data['status'] = 'open';

        Feedback::create($data);

        return back()->with('status', 'Feedback sent. Thank you for helping us improve.');
    }
}
