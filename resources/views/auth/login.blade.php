<x-guest-layout>
    <style>
        .auth-logo-mark {
            width: 60px;
            height: 60px;
            border-radius: 18px;
            display: grid;
            place-items: center;
            background: linear-gradient(135deg, #0ea5e9, #2563eb);
            color: #fff;
            box-shadow: 0 24px 40px -24px rgba(37,99,235,0.45);
            margin-bottom: 1rem;
        }
        .auth-logo-mark .mdi {
            font-size: 1.8rem;
        }
    </style>
    <div class="auth-form-pane-inner">
        <div class="auth-logo-mark">
            <span class="mdi mdi-coffee-outline"></span>
        </div>
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
