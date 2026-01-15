<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use Exception;

class SocialAuthController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Check if user already exists
            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user) {
                // Update existing user's Google ID if not set
                if (!$user->google_id) {
                    $user->google_id = $googleUser->getId();
                    $user->avatar = $googleUser->getAvatar();
                    $user->save();
                }
            } else {
                // Create new user
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                    'password' => Hash::make(Str::random(24)), // Random password
                    'email_verified_at' => now(), // Auto-verify email for Google users
                ]);
            }

            // Log the user in
            Auth::login($user, true);

            // Redirect to dashboard or home
            return redirect()->route('index')->with('success', __('lang.login_successful'));

        } catch (Exception $e) {
            return redirect()->route('login')->with('error', __('lang.google_login_failed') . ': ' . $e->getMessage());
        }
    }
}
