<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;


class ConfirmablePasswordController extends Controller
{
    
    public function show()
    {
        return view('auth.confirm-password');
    }

    
    public function store(Request $request)
    {
        
        $credentialeValide = Auth::guard('web')->validate([
            'email'    => $request->user()->email,
            'password' => $request->password,
        ]);

        
        if (!$credentialeValide) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        
        $request->session()->put('auth.password_confirmed_at', time());

        
        return redirect()->intended(route('dashboard', [], false));
    }
}
