<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback($provider)
    {
        $socialUser = Socialite::driver($provider)->stateless()->user();

        if (!$socialUser || !isset($socialUser->getId)) {
            return redirect()->route('login')->withErrors(['oauth' => 'Unable to authenticate with ' . $provider]);
        }

        $existing = User::where('email', $socialUser->getEmail())->orWhere('google_id', $socialUser->getId())->first();

        if ($existing) {
            // update google id/avatar if missing
            $existing->google_id = $existing->google_id ?: $socialUser->getId();
            $existing->avatar = $socialUser->getAvatar();
            $existing->save();
            Auth::login($existing);
            return redirect()->intended('/dashboard');
        }

        // create new user
        $user = User::create([
            'name' => $socialUser->getName() ?: $socialUser->getNickname() ?: 'Google User',
            'email' => $socialUser->getEmail(),
            'password' => Hash::make(str()->random(24)),
            'google_id' => $socialUser->getId(),
            'avatar' => $socialUser->getAvatar(),
        ]);

        Auth::login($user);

        return redirect()->intended('/dashboard');
    }
}
