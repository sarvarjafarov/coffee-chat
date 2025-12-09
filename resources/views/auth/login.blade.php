<x-guest-layout>
    <div class="auth-form-pane-inner">
        <h2>Welcome back</h2>
        <p class="auth-lead">Log in to orchestrate your next coffee chat, sync follow-ups, and keep momentum. Don’t have an account? <a class="auth-link" href="{{ route('register') }}">Start free</a>.</p>

        @if (!empty($adminPrompt))
            <div class="auth-status auth-status--info">{{ $adminPrompt }}</div>
        @endif

        @if (session('status'))
            <div class="auth-status">{{ session('status') }}</div>
        @endif

        @include('auth.partials.social-login', [
            'googleText' => 'Sign in with Google',
            'linkedinText' => 'Sign in with LinkedIn',
            'dividerText' => 'Or continue with your password',
        ])

        <form
            method="POST"
            action="{{ route('login') }}"
            class="auth-form"
            data-analytics-event="auth_submit"
            data-context="login"
        >
            @csrf

            <div class="auth-form-group">
                <label for="login">Email or Username</label>
                <input id="login" type="text" name="login" value="{{ old('login') }}" required autofocus autocomplete="username" class="auth-input">
                @error('login')
                    <p class="auth-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="auth-form-group">
                <label for="password">Password</label>
                <input id="password" type="password" name="password" required autocomplete="current-password" class="auth-input">
                @error('password')
                    <p class="auth-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="auth-inline">
                <label for="remember_me" class="auth-checkbox">
                    <input id="remember_me" type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                    <span>{{ __('Remember me') }}</span>
                </label>
                @if (Route::has('password.request'))
                    <a class="auth-link auth-link--muted" href="{{ route('password.request') }}">{{ __('Forgot your password?') }}</a>
                @endif
            </div>

            <button type="submit" class="auth-btn">{{ __('Log in') }}</button>
        </form>
    </div>
</x-guest-layout>
