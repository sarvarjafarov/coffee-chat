<?php

namespace App\Services;

use App\Models\StripeSetting;
use Stripe\StripeClient;
use Stripe\Exception\ApiErrorException;

class StripeService
{
    protected StripeSetting $settings;

    public function __construct(?StripeSetting $settings = null)
    {
        $this->settings = $settings ?? StripeSetting::current();
    }

    public function isConfigured(): bool
    {
        return filled($this->settings->secret_key) && filled($this->settings->publishable_key) && filled($this->settings->price_id);
    }

    public function publishableKey(): ?string
    {
        return $this->settings->publishable_key;
    }

    protected function client(): StripeClient
    {
        return new StripeClient($this->settings->secret_key);
    }

    /**
     * @throws ApiErrorException
     */
    public function createCheckoutSession(string $customerEmail, string $successUrl, string $cancelUrl, ?string $customerId = null): array
    {
        $client = $this->client();

        if (! $customerId) {
            $customer = $client->customers->create([
                'email' => $customerEmail,
            ]);
            $customerId = $customer->id;
        }

        $session = $client->checkout->sessions->create([
            'mode' => 'subscription',
            'payment_method_types' => ['card'],
            'customer' => $customerId,
            'line_items' => [[
                'price' => $this->settings->price_id,
                'quantity' => 1,
            ]],
            'success_url' => $successUrl.'?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => $cancelUrl,
        ]);

        return [
            'session_id' => $session->id,
            'customer_id' => $customerId,
            'url' => $session->url,
        ];
    }

    /**
     * @throws ApiErrorException
     */
    public function retrieveSession(string $sessionId)
    {
        return $this->client()->checkout->sessions->retrieve($sessionId, []);
    }

    /**
     * @throws ApiErrorException
     */
    public function retrieveSubscription(string $subscriptionId)
    {
        return $this->client()->subscriptions->retrieve($subscriptionId, []);
    }
}
