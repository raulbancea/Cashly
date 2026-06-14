<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Symfony\Component\Mailer\Exception\TransportException;


class PasswordResetLinkController extends Controller
{
    
    public function create()
    {
        return view('auth.forgot-password');
    }

    
    public function store(Request $request)
    {
        
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        
        
        try {
            $status = Password::sendResetLink(
                $request->only('email')
            );
        } catch (TransportException $e) {
            return back()->with('status', __('passwords.sent'));
        }

        if ($status == Password::RESET_LINK_SENT) {
            return back()->with('status', __($status));
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => __($status)]);
    }
}
