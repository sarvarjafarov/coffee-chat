<?php

namespace App\Http\Controllers;

use App\Models\StripeSetting;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PricingController extends Controller
{
    public function __invoke(): View
    {
        $stripeSettings = StripeSetting::current();

        return view('pricing.index', [
            'stripeConfigured' => filled($stripeSettings->publishable_key) && filled($stripeSettings->price_id),
            'publishableKey' => $stripeSettings->publishable_key,
            'user' => Auth::user(),
        ]);
    }
}
