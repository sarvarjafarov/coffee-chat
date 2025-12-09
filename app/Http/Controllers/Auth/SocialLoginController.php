<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialLoginController extends Controller
{
    /**
     * Supported OAuth providers.
     *
     * @var array<int, string>
     */
    protected array $providers = ['google', 'linkedin'];

    public function redirect(string $provider): RedirectResponse
    {
        $provider = $this->validatedProvider($provider);
        $driverName = $this->driverName($provider);

        $config = $this->resolveProviderConfig($provider);

        if ($config === null) {
            return redirect()
                ->route('login')
                ->with('status', $this->displayName($provider).' sign-in is not configured yet. Please contact the workspace admin.');
        }

        $driver = Socialite::driver($driverName);

        // LinkedIn app is configured for OpenID Connect; request only OIDC scopes explicitly.
        $driver->setScopes(['openid', 'profile', 'email'])
            ->with(['scope' => 'openid profile email']);

        $redirectUrl = data_get($config, 'redirect') ?: $this->callbackUrl($provider);

        $driver->redirectUrl($redirectUrl);

        return $driver->with([
            'client_id' => $config['client_id'],
        ])->redirect();
    }

    public function callback(Request $request, string $provider): RedirectResponse
    {
        $provider = $this->validatedProvider($provider);

        $config = $this->resolveProviderConfig($provider);

        if ($config === null) {
            return redirect()
                ->route('login')
                ->with('status', $this->displayName($provider).' sign-in is not configured yet. Please contact the workspace admin.');
        }

        try {
            $driver = Socialite::driver($this->driverName($provider));

            $redirectUrl = data_get($config, 'redirect') ?: $this->callbackUrl($provider);
            $driver->redirectUrl($redirectUrl);

            $socialUser = $driver->stateless()->user();
        } catch (\Throwable $exception) {
            report($exception);

            return redirect()
                ->route('login')
                ->with('status', 'We could not sign you in with '.$this->displayName($provider).'. Please try again.');
        }

        $providerId = $socialUser->getId();
        $email = $socialUser->getEmail();

        if (! $providerId) {
            return redirect()
                ->route('login')
                ->with('status', 'We could not validate your '.$this->displayName($provider).' account. Please try another method.');
        }

        $user = User::query()
            ->where('oauth_provider', $provider)
            ->where('oauth_id', $providerId)
            ->first();

        if (! $user && $email) {
            $user = User::query()->where('email', $email)->first();
        }

        if (! $user) {
            $user = User::create([
                'name' => $socialUser->getName() ?: $socialUser->getNickname() ?: Str::headline($provider).' User',
                'email' => $email ?: $this->fallbackEmail($provider, $providerId),
                'password' => Str::random(40),
            ]);
        }

        $user->forceFill([
            'oauth_provider' => $provider,
            'oauth_id' => $providerId,
        ]);

        if ($email && $user->email !== $email) {
            $user->email = $email;
        }

        $verified = data_get($socialUser->user ?? [], 'verified_email')
            ?? data_get($socialUser->user ?? [], 'email_verified')
            ?? ($provider === 'linkedin' ? true : null);

        if ($verified && ! $user->hasVerifiedEmail()) {
            $user->email_verified_at = now();
        }

        if ($avatar = $socialUser->getAvatar()) {
            $user->avatar_url = $avatar;
        }

        $user->save();

        Auth::login($user, true);

        return redirect()->intended(route('workspace.coffee-chats.index'));
    }

    protected function validatedProvider(string $provider): string
    {
        if (! in_array($provider, $this->providers, true)) {
            abort(404);
        }

        return $provider;
    }

    protected function fallbackEmail(string $provider, string $providerId): string
    {
        return sprintf('%s_%s@oauth.local', $provider, $providerId);
    }

    protected function displayName(string $provider): string
    {
        return match ($provider) {
            'google' => 'Google',
            'linkedin' => 'LinkedIn',
            default => Str::headline($provider),
        };
    }

    /**
     * @return array<string, mixed>|null
     */
    protected function resolveProviderConfig(string $provider): ?array
    {
        $serviceKey = $this->serviceKey($provider);

        $config = config("services.{$serviceKey}", config("services.{$provider}", []));

        // Ensure redirect is always set to a valid callback URL when client credentials exist.
        if (
            ! blank(data_get($config, 'client_id'))
            && ! blank(data_get($config, 'client_secret'))
            && blank(data_get($config, 'redirect'))
        ) {
            $config['redirect'] = $this->callbackUrl($provider);
            config(["services.{$serviceKey}.redirect" => $config['redirect']]);
        }

        if (! blank(data_get($config, 'client_id')) && ! blank(data_get($config, 'client_secret'))) {
            return $config;
        }

        $keys = match ($provider) {
            'google' => [
                'client_id' => 'google_client_id',
                'client_secret' => 'google_client_secret',
                'redirect' => 'google_redirect_uri',
            ],
            'linkedin' => [
                'client_id' => 'linkedin_client_id',
                'client_secret' => 'linkedin_client_secret',
                'redirect' => 'linkedin_redirect_uri',
            ],
            default => [],
        };

        if (empty($keys)) {
            return null;
        }

        $settings = SiteSetting::query()
            ->whereIn('key', array_values($keys))
            ->pluck('value', 'key');

        if (
            blank($settings->get($keys['client_id']))
            || blank($settings->get($keys['client_secret']))
        ) {
            return null;
        }

        $resolved = [
            'client_id' => $settings->get($keys['client_id']),
            'client_secret' => $settings->get($keys['client_secret']),
            'redirect' => $settings->get($keys['redirect'], data_get($config, 'redirect')),
        ];

        if (blank($resolved['redirect'])) {
            $resolved['redirect'] = $this->callbackUrl($provider);
        }

        foreach ($this->configTargets($provider) as $target) {
            config(["services.{$target}.client_id" => $resolved['client_id']]);
            config(["services.{$target}.client_secret" => $resolved['client_secret']]);
            if (! blank($resolved['redirect'])) {
                config(["services.{$target}.redirect" => $resolved['redirect']]);
            }
        }

        return config("services.{$serviceKey}", []);
    }

    protected function callbackUrl(string $provider): string
    {
        // Build callback from the named route to respect current scheme/host and avoid redirect mismatches.
        return route('oauth.callback', ['provider' => $provider], true);
    }

    protected function driverName(string $provider): string
    {
        return $provider === 'linkedin' ? 'linkedin-openid' : $provider;
    }

    protected function serviceKey(string $provider): string
    {
        return $provider === 'linkedin' ? 'linkedin-openid' : $provider;
    }

    /**
     * @return array<int, string>
     */
    protected function configTargets(string $provider): array
    {
        $serviceKey = $this->serviceKey($provider);

        if ($serviceKey === $provider) {
            return [$serviceKey];
        }

        return [$serviceKey, $provider];
    }
}
