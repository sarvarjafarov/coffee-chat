@php
    $dividerText = $dividerText ?? 'Or continue with email';
    $googleText = $googleText ?? 'Continue with Google';
    $linkedinText = $linkedinText ?? 'Continue with LinkedIn';
@endphp

<div class="auth-social">
    <a
        href="{{ route('oauth.redirect', ['provider' => 'google']) }}"
        class="auth-social-btn auth-social-btn--google"
        data-analytics-event="social_login_click"
        data-provider="google"
    >
        <span class="auth-social-icon"><span class="auth-social-icon-letter">G</span></span>
        <span>{{ $googleText }}</span>
    </a>
    <a
        href="{{ route('oauth.redirect', ['provider' => 'linkedin']) }}"
        class="auth-social-btn auth-social-btn--linkedin"
        data-analytics-event="social_login_click"
        data-provider="linkedin"
    >
        <span class="auth-social-icon"><span class="auth-social-icon-letter">in</span></span>
        <span>{{ $linkedinText }}</span>
    </a>
</div>
<div class="auth-divider"><span>{{ $dividerText }}</span></div>
