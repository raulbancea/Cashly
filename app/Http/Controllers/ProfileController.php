<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;


class ProfileController extends Controller
{
    
    public function edit(Request $request)
    {
        
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    
    public function update(ProfileUpdateRequest $request)
    {
        
        $request->user()->fill($request->validated());

        
        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        
        $request->user()->save();

        
        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    
    public function updateAvatar(Request $request)
    {
        
        $request->validate(['avatar' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048']);

        
        $user = $request->user();

        
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        
        $folderAvatar = 'avatars/' . $user->id;
        $path = $request->file('avatar')->store($folderAvatar, 'public');

        
        $user->update(['avatar' => $path]);

        
        return Redirect::route('profile.edit')->with('success', 'Fotografia de profil a fost actualizată!');
    }

    
    public function removeAvatar(Request $request)
    {
        
        $user = $request->user();

        
        if ($user->avatar) {
            
            Storage::disk('public')->delete($user->avatar);
            
            $user->update(['avatar' => null]);
        }

        
        return Redirect::route('profile.edit')->with('success', 'Fotografia de profil a fost ștearsă.');
    }

    
    public function destroy(Request $request)
    {
        
        
        if ($request->user()->google_id) {
            $rules = ['password' => ['nullable']];
        } else {
            $rules = ['password' => ['required', 'current_password']];
        }

        
        $request->validateWithBag('userDeletion', $rules);

        
        $user = $request->user();

        
        Auth::logout();

        
        $user->delete();

        
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        
        return Redirect::to('/');
    }
}
