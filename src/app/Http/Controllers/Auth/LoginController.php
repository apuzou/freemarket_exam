<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            
            if (!Auth::user()->hasVerifiedEmail()) {
                session(['pending_verification_user_id' => Auth::user()->id]);
                Auth::user()->sendEmailVerificationNotification();
                Auth::logout();
                return redirect()->route('verification.notice')->with('resent', true);
            }
            
            if (Auth::user()->hasCompletedProfile()) {
                return redirect()->intended('/');
            } else {
                return redirect()->route('mypage.profile');
            }
        }

        throw ValidationException::withMessages([
            'email' => 'メールアドレスまたはパスワードが正しくありません',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
