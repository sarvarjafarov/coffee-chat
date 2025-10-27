# Pricing & Subscription Architecture

## Overview

CoffeeChat OS now operates as a freemium product with two plans:

| Plan    | Monthly price | Limits | Entitlements |
|---------|---------------|--------|--------------|
| Free    | $0            | Up to **10** active coffee chat records | Access to Team Finder, Network Health Analysis, MBA Jobs preview |
| Premium | $10           | Unlimited coffee chats | Everything in Free, priority updates, unlocked automations (future) |

The application enforces these limits server-side and exposes upgrade flows powered by Stripe Checkout.

## Core Components

1. **Plan metadata on `users`**
   - New columns: `plan` (enum-like string), `stripe_customer_id`, `stripe_subscription_id`, `plan_expires_at`.
   - Convenience helpers on the `User` model (`isPremium()`, `isFree()`).
   - Default plan is `free`.

2. **Stripe Integration**
   - Credentials (publishable key, secret key, price ID, webhook secret) stored in a dedicated `stripe_settings` table (managed via Admin > Stripe Integration panel).
   - Checkout flow uses Stripe Checkout Sessions. Successful payment upgrades the user to `premium`.
   - Webhook endpoint prepared for future automation; current MVP validates success via session lookup after redirect.

3. **Subscription Workflow**
   - Pricing page (`/pricing`) presents plan comparison, upgrade CTA.
   - Authenticated users hit `POST /subscription/checkout` which:
     - Validates Stripe settings are present.
     - Creates or reuses a Stripe Customer.
     - Creates a Checkout Session targeting the configured price.
     - Redirects user to Stripe hosted flow.
   - `GET /subscription/success` validates the session and updates the user’s plan.
   - `GET /subscription/cancelled` informs the user and keeps them on the free plan.

4. **Usage Enforcement**
   - When free users attempt to log the 11th coffee chat, the controller aborts the create/store action and redirects to pricing with an explanatory flash message.
   - Premium users bypass the limit.

5. **Admin Visibility & Controls**
   - Admin sidebar now includes “Network health” and “Stripe integration”.
   - Stripe integration panel enables setting/updating API keys without touching `.env`.
   - Future: Stripe webhook events can feed into the admin dashboard for revenue reporting.

## Sequence Diagram

```
[User] -> View pricing page -> [Browser]
[Browser] -> Upgrade request -> POST /subscription/checkout
[App] -> Loads Stripe keys from DB
[App] -> Create/Retrieve Customer -> [Stripe API]
[Stripe API] -> Customer ID -> [App]
[App] -> Create Checkout Session -> [Stripe API]
[Stripe API] -> Session URL -> [App]
[App] -> Redirect 303 -> [Browser]
[Browser] -> Stripe Checkout -> [Stripe Hosted]
[User] -> Completes payment -> [Stripe Hosted]
[Stripe Hosted] -> Redirect success -> /subscription/success?session_id=...
[App] -> Retrieve session -> [Stripe API]
[Stripe API] -> session.status=complete -> [App]
[App] -> Update user plan=“premium” -> [DB]
[App] -> Render success view -> [Browser]
```

## Roadmap Considerations

- Add Stripe webhooks to automatically react to cancellations/delinquent payments.
- Support annual pricing by storing multiple price IDs.
- Extend limits to other features (follow-up automations, analytics exports) by referencing `User::plan`.
- Introduce `feature_flags` table to toggle premium previews as the product grows.
