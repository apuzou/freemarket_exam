<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request)
    {
        $validated = $request->validated();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        Profile::create([
            'user_id' => $user->id,
            'postal_code' => '',
            'address' => '',
            'building' => null,
            'profile_image' => null,
        ]);

        session(['pending_verification_user_id' => $user->id]);

        $user->sendEmailVerificationNotification();

        Auth::logout();

        return redirect()->route('verification.notice');
    }
}
