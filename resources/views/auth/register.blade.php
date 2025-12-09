<x-guest-layout>
    <style>
        .auth-logo-mark {
            width: 60px;
            height: 60px;
            border-radius: 18px;
            background: url('{{ asset('coffeechat-os-favicon.png?v=3') }}') center center / 90% 90% no-repeat, linear-gradient(135deg, #0ea5e9, #2563eb);
            box-shadow: 0 24px 40px -24px rgba(37,99,235,0.45);
            margin-bottom: 1rem;
        }
    </style>
    <div class="auth-form-pane-inner">
        <div class="auth-logo-mark"></div>
        <h2>Create your workspace</h2>
        <p class="auth-lead">Spin up CoffeeChat OS for your cohort or team. Already have an account? <a class="auth-link" href="{{ route('login') }}">Log in</a>.</p>

        @include('auth.partials.social-login', [
            'googleText' => 'Sign up with Google',
            'linkedinText' => 'Sign up with LinkedIn',
            'dividerText' => 'Or create your account with email',
        ])

        <form
            method="POST"
            action="{{ route('register') }}"
            class="auth-form"
            data-analytics-event="auth_submit"
            data-context="register"
        >
            @csrf

            <div class="auth-form-group">
                <label for="name">Name</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" class="auth-input">
                @error('name')
                    <p class="auth-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="auth-form-group">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" class="auth-input">
                @error('email')
                    <p class="auth-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="auth-form-group">
                <label for="password">Password</label>
                <input id="password" type="password" name="password" required autocomplete="new-password" class="auth-input">
                @error('password')
                    <p class="auth-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="auth-form-group">
                <label for="password_confirmation">Confirm Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" class="auth-input">
                @error('password_confirmation')
                    <p class="auth-error">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="auth-btn">{{ __('Create account') }}</button>
        </form>
    </div>
</x-guest-layout>
