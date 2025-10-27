<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    // ログイン画面表示
    public function create()
    {
        return view('auth.login');
    }

    // ログイン処理
    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            if (Auth::user()->hasVerifiedEmail() === false) {
                session(['pending_verification_user_id' => Auth::user()->id]);
                Auth::user()->sendEmailVerificationNotification();
                Auth::logout();
                return redirect()->route('verification.notice')->with('resent', true);
            }

            if (Auth::user()->hasCompletedProfile() === true) {
                return redirect()->intended('/');
            } else {
                return redirect()->route('mypage.profile');
            }
        }

        throw ValidationException::withMessages([
            'email' => 'ログイン情報が登録されていません',
        ]);
    }

    // ログアウト処理
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
