<?php

namespace App\Http\Controllers;

use App\Models\StripeSetting;
use App\Services\StripeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Stripe\Exception\ApiErrorException;

class SubscriptionController extends Controller
{
    public function checkout(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->isPremium()) {
            return redirect()->route('pricing')->with('status', 'You are already on the premium plan.');
        }

        $stripe = new StripeService();

        if (! $stripe->isConfigured()) {
            return redirect()->route('pricing')->withErrors('Stripe is not configured yet. Please try again later.');
        }

        try {
            $session = $stripe->createCheckoutSession(
                $user->email,
                route('subscription.success'),
                route('subscription.cancelled'),
                $user->stripe_customer_id
            );

            if (! $user->stripe_customer_id && isset($session['customer_id'])) {
                $user->update(['stripe_customer_id' => $session['customer_id']]);
            }

            return redirect()->away($session['url']);
        } catch (ApiErrorException $e) {
            Log::error('Stripe checkout failed', ['exception' => $e]);
            return redirect()->route('pricing')->withErrors('Unable to start checkout. Please verify Stripe credentials.');
        }
    }

    public function success(Request $request, StripeService $stripeService): RedirectResponse
    {
        $sessionId = $request->query('session_id');
        if (! $sessionId) {
            return redirect()->route('pricing')->withErrors('Stripe session missing.');
        }

        try {
            $session = $stripeService->retrieveSession($sessionId);
            if ($session->status !== 'complete') {
                return redirect()->route('pricing')->withErrors('Checkout not completed yet.');
            }

            $subscriptionId = $session->subscription;
            if ($subscriptionId) {
                $subscription = $stripeService->retrieveSubscription($subscriptionId);
                $currentPeriodEnd = $subscription->current_period_end ? now()->setTimestamp($subscription->current_period_end) : null;
                $user = Auth::user();
                $user->update([
                    'plan' => 'premium',
                    'stripe_subscription_id' => $subscriptionId,
                    'stripe_customer_id' => $session->customer,
                    'plan_expires_at' => $currentPeriodEnd,
                ]);
            }

            return redirect()->route('workspace.coffee-chats.index')->with('status', 'Welcome to Premium! Unlimited coffee chats unlocked.');
        } catch (ApiErrorException $e) {
            Log::error('Stripe success handler failed', ['exception' => $e]);
            return redirect()->route('pricing')->withErrors('Unable to validate payment. Contact support.');
        }
    }

    public function cancelled(): RedirectResponse
    {
        return redirect()->route('pricing')->with('status', 'Checkout cancelled — you remain on the free plan.');
    }

    public function settings(): View
    {
        $settings = StripeSetting::current();

        return view('admin.stripe.settings', [
            'settings' => $settings,
        ]);
    }

    public function updateSettings(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'publishable_key' => ['nullable', 'string', 'max:255'],
            'secret_key' => ['nullable', 'string', 'max:255'],
            'price_id' => ['nullable', 'string', 'max:255'],
            'webhook_secret' => ['nullable', 'string', 'max:255'],
        ]);

        $settings = StripeSetting::current();
        $settings->fill($data);
        $settings->save();

        return redirect()->route('admin.stripe.settings')->with('status', 'Stripe settings updated.');
    }
}
