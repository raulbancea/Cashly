<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    public function callback()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

        // 1. Cauta userul dupa google_id
        $user = User::where('google_id', $googleUser->getId())->first();

        $accountLinked = false;

        if (!$user) {
            // 2. Verifica daca emailul exista deja (cont creat manual)
            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user) {
                // Leaga contul Google la contul existent
                $user->update([
                    'google_id' => $googleUser->getId(),
                    'avatar'    => $googleUser->getAvatar(),
                ]);
                $accountLinked = true;
            } else {
                // 3. Creeaza cont nou din Google
                $user = User::create([
                    'name'              => $googleUser->getName(),
                    'email'             => $googleUser->getEmail(),
                    'google_id'         => $googleUser->getId(),
                    'avatar'            => $googleUser->getAvatar(),
                    'password'          => null,
                    'email_verified_at' => now(),
                    'trial_ends_at'     => now()->addDays(30),
                ]);
            }
        }

        // Actualizeaza avatarul la fiecare login (poate fi schimbat pe Google)
        $user->update(['avatar' => $googleUser->getAvatar()]);

        Auth::login($user, remember: true);

        if ($accountLinked) {
            return redirect()->intended(route('dashboard'))
                ->with('success', 'Contul tău existent a fost conectat cu Google. Poți folosi ambele metode de autentificare.');
        }

        return redirect()->intended(route('dashboard'));
    }
}
