<x-guest-layout>
    <div class="auth-form-pane-inner">
        <h2>Reset your password</h2>
        <p class="auth-lead">Forgot your password? Enter the email tied to your workspace and we’ll send a reset link. Remember it now? <a class="auth-link" href="{{ route('login') }}">Back to log in</a>.</p>

        @if (session('status'))
            <div class="auth-status">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="auth-form">
            @csrf

            <div class="auth-form-group">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus class="auth-input">
                @error('email')
                    <p class="auth-error">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="auth-btn">{{ __('Email Password Reset Link') }}</button>
        </form>
    </div>
</x-guest-layout>