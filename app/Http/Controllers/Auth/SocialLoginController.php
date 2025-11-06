<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
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

        $config = config("services.{$provider}", []);

        if (blank(data_get($config, 'client_id')) || blank(data_get($config, 'client_secret'))) {
            return redirect()
                ->route('login')
                ->with('status', $this->displayName($provider).' sign-in is not configured yet. Please contact the workspace admin.');
        }

        $driver = Socialite::driver($provider);

        if ($provider === 'linkedin') {
            $driver->scopes(['r_liteprofile', 'r_emailaddress']);
        } else {
            $driver->scopes(['openid', 'profile', 'email']);
        }

        if (filled(data_get($config, 'redirect'))) {
            $driver->redirectUrl($config['redirect']);
        }

        return $driver->with([
            'client_id' => $config['client_id'],
        ])->redirect();
    }

    public function callback(Request $request, string $provider): RedirectResponse
    {
        $provider = $this->validatedProvider($provider);

        $config = config("services.{$provider}", []);

        try {
            $driver = Socialite::driver($provider);

            if (filled(data_get($config, 'redirect'))) {
                $driver->redirectUrl($config['redirect']);
            }

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
}
