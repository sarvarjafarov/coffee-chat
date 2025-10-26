<x-guest-layout>
    <div class="auth-form-pane-inner">
        <h2>Verify your email</h2>
        <p class="auth-lead">Thanks for signing up! Before getting started, click the link we just emailed you. Didn’t get it? Request another verification email below.</p>

        @if (session('status') == 'verification-link-sent')
            <div class="auth-status">{{ __('A new verification link has been sent to the email address on file.') }}</div>
        @endif

        <div class="auth-inline-actions">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="auth-btn">{{ __('Resend verification email') }}</button>
            </form>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="auth-btn auth-btn--ghost">{{ __('Log out') }}</button>
            </form>
        </div>
    </div>
</x-guest-layout>