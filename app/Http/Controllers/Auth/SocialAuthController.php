<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;

class SocialAuthController extends Controller
{
    public function redirectToProvider(string $provider)
    {
        if ($provider !== 'google') {
            abort(404);
        }

        return Socialite::driver('google')->redirect();
    }

    public function handleProviderCallback(string $provider)
    {
        if ($provider !== 'google') {
            abort(404);
        }

        try {
            $socialUser = Socialite::driver('google')->stateless()->user();
        } catch (InvalidStateException $e) {
            return redirect()->route('login')
                ->withErrors(['oauth' => 'OAuth state error. Try again.']);
        } catch (\Throwable $e) {
            return redirect()->route('login')
                ->withErrors(['oauth' => 'Could not complete Google login.']);
        }

        if (!$socialUser || !$socialUser->getId()) {
            return redirect()->route('login')
                ->withErrors(['oauth' => 'Unable to authenticate with Google.']);
        }

        $googleId = $socialUser->getId();
        $email    = $socialUser->getEmail();
        $name     = $socialUser->getName() ?: ($socialUser->getNickname() ?: 'Google User');
        $avatar   = $socialUser->getAvatar();

        // Check if user already exists
        $user = User::where('google_id', $googleId)
            ->orWhere(function ($q) use ($email) {
                if ($email) {
                    $q->where('email', $email);
                }
            })
            ->first();

        if ($user) {
            if (!$user->google_id) {
                $user->google_id = $googleId;
            }
            if ($avatar && $user->avatar !== $avatar) {
                $user->avatar = $avatar;
            }
            $user->save();

            Auth::login($user, remember: true);
            return redirect()->intended('/dashboard');
        }

        // Create new user
        $user = User::create([
            'name'      => $name,
            'email'     => $email,
            'password'  => Hash::make(str()->random(32)),
            'google_id' => $googleId,
            'avatar'    => $avatar,
        ]);

        Auth::login($user, remember: true);

        return redirect()->intended('/dashboard');
    }
}
