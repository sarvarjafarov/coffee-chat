<x-guest-layout>
    <div class="auth-form-pane-inner">
        <h2>Choose a new password</h2>
        <p class="auth-lead">Set a fresh password to get back into CoffeeChat OS.</p>

        <form
            method="POST"
            action="{{ route('password.store') }}"
            class="auth-form"
            data-analytics-event="auth_submit"
            data-context="reset_password"
        >
            @csrf
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div class="auth-form-group">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autocomplete="username" class="auth-input">
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

            <button type="submit" class="auth-btn">{{ __('Reset Password') }}</button>
        </form>
    </div>
</x-guest-layout>
