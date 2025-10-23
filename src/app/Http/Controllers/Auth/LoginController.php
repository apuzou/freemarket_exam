<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * ログイン画面を表示
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * ログイン処理
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            
            // メール認証が完了していない場合は認証誘導画面へ
            if (!Auth::user()->hasVerifiedEmail()) {
                // セッションにユーザーIDを保存
                session(['pending_verification_user_id' => Auth::user()->id]);
                // 認証メールを再送信
                Auth::user()->sendEmailVerificationNotification();
                Auth::logout();
                return redirect()->route('verification.notice')->with('resent', true);
            }
            
            // メール認証済みの場合、住所登録状況をチェック
            if (Auth::user()->hasCompletedProfile()) {
                // 住所登録済み → ホーム画面
                return redirect()->intended('/');
            } else {
                // 住所未登録 → プロフィール設定画面
                return redirect()->route('mypage.profile');
            }
        }

        throw ValidationException::withMessages([
            'email' => 'メールアドレスまたはパスワードが正しくありません',
        ]);
    }

    /**
     * ログアウト処理
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
