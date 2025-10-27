@extends('layouts.admin')

@section('title', 'Stripe Integration')

@section('content')
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.stripe.settings.update') }}">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="publishable_key">Publishable key</label>
                    <input type="text" id="publishable_key" name="publishable_key" class="form-control" value="{{ old('publishable_key', $settings->publishable_key) }}" placeholder="pk_live_...">
                </div>
                <div class="form-group">
                    <label for="secret_key">Secret key</label>
                    <input type="text" id="secret_key" name="secret_key" class="form-control" value="{{ old('secret_key', $settings->secret_key) }}" placeholder="sk_live_...">
                </div>
                <div class="form-group">
                    <label for="price_id">Price ID</label>
                    <input type="text" id="price_id" name="price_id" class="form-control" value="{{ old('price_id', $settings->price_id) }}" placeholder="price_123">
                    <small class="form-text text-muted">Create a recurring price in Stripe Dashboard (monthly $10) and paste the price ID here.</small>
                </div>
                <div class="form-group">
                    <label for="webhook_secret">Webhook secret (optional)</label>
                    <input type="text" id="webhook_secret" name="webhook_secret" class="form-control" value="{{ old('webhook_secret', $settings->webhook_secret) }}" placeholder="whsec_...">
                </div>
                <button type="submit" class="btn btn-primary">Save settings</button>
            </form>
        </div>
    </div>
@endsection
