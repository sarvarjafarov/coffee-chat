<x-guest-layout>
    <div class="auth-form-pane-inner">
        <h2>Confirm password</h2>
        <p class="auth-lead">This is a secure area. Please confirm your password before continuing.</p>

        <form
            method="POST"
            action="{{ route('password.confirm') }}"
            class="auth-form"
            data-analytics-event="auth_submit"
            data-context="confirm_password"
        >
            @csrf
            <div class="auth-form-group">
                <label for="password">Password</label>
                <input id="password" type="password" name="password" required autocomplete="current-password" class="auth-input">
                @error('password')
                    <p class="auth-error">{{ $message }}</p>
                @enderror
            </div>
            <button type="submit" class="auth-btn">{{ __('Confirm') }}</button>
        </form>
    </div>
</x-guest-layout>
