<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;


class RegisteredUserController extends Controller
{
    
    public function create()
    {
        return view('auth.register');
    }

    
    public function store(Request $request)
    {
        
        $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'company_name' => ['required', 'string', 'max:255'],
            'company_vat'  => ['nullable', 'string', 'max:255'],
            'phone'        => ['nullable', 'string', 'max:255'],
            'address'      => ['nullable', 'string', 'max:255'],
            'email'        => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password'     => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        
        
        
        $user = User::create([
            'name'          => $request->name,
            'company_name'  => $request->company_name,
            'company_vat'   => $request->company_vat,
            'phone'         => $request->phone,
            'address'       => $request->address,
            'currency'      => 'EUR',
            'plan'          => 'free',
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
            'trial_ends_at' => now()->addDays(30),
        ]);

        
        event(new Registered($user));

        
        Auth::login($user);

        
        return redirect(route('dashboard', [], false))
            ->with('success', 'Bun venit pe Cashly, ' . $user->name . '! Contul tău a fost creat cu succes.');
    }
}
