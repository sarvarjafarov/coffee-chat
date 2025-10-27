<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NetworkHealthAssessment;
use Illuminate\View\View;

class NetworkHealthAssessmentController extends Controller
{
    public function index(): View
    {
        $assessments = NetworkHealthAssessment::latest()->paginate(20);

        return view('admin.network-health.index', [
            'assessments' => $assessments,
        ]);
    }
}
