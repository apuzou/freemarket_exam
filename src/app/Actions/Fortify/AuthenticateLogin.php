<?php

namespace App\Actions\Fortify;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthenticateLogin
{
    /**
     * Handle an incoming authentication request.
     * バリデーションはAuthenticatedSessionControllerで実行される
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function __invoke(Request $request)
    {

        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials, $request->filled('remember'))) {
            throw ValidationException::withMessages([
                'email' => 'ログイン情報が登録されていません',
            ]);
        }

        $request->session()->regenerate();

        $user = Auth::user();

        // メール認証チェック
        if ($user->hasVerifiedEmail() === false) {
            session(['pending_verification_user_id' => $user->id]);
            $user->sendEmailVerificationNotification();
            Auth::logout();
            return redirect()->route('verification.notice')->with('resent', true);
        }

        // ログイン成功（リダイレクトはCustomLoginResponseで処理）
        return $user;
    }
}

