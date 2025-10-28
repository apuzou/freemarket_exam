<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\VerifyCodeRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Verified;

class VerificationController extends Controller
{
    // メール認証誘導画面
    public function notice()
    {
        if (Auth::check() && Auth::user()->hasVerifiedEmail()) {
            return $this->redirectAuthenticatedUser(Auth::user());
        }

        return view('auth.email-verification-notice');
    }

    // 確認コード入力画面
    public function showCodeInput()
    {
        $user = $this->getUserFromSession();
        
        if ($user === null) {
            return redirect()->route('login')->with('error', 'セッションが期限切れです。再度ログインしてください。');
        }

        if ($user->hasVerifiedEmail()) {
            return $this->redirectAuthenticatedUser($user);
        }

        return view('auth.verify-code');
    }

    // 確認コード検証
    public function verifyCode(VerifyCodeRequest $request)
    {
        $user = $this->getUserFromSession();
        
        if ($user === null) {
            return back()->with('error', 'セッションが期限切れです。再度ログインしてください。');
        }

        if ($user->hasVerifiedEmail()) {
            return $this->redirectAuthenticatedUser($user);
        }

        // 確認コードの有効期限チェック
        if (!$user->verification_code_expires_at || $user->verification_code_expires_at->isPast()) {
            return back()->with('error', '確認コードの有効期限が切れています。再度認証メールを送信してください。');
        }

        // 確認コードの検証
        if (Hash::check($request->validated()['code'], $user->verification_code)) {
            // メール認証完了
            if ($user->markEmailAsVerified()) {
                event(new Verified($user));
            }

            // 確認コードをクリア
            $user->verification_code = null;
            $user->verification_code_expires_at = null;
            $user->save();

            Auth::login($user);
            return $this->redirectAuthenticatedUser($user)->with('verified', true);
        }

        return back()->with('error', '確認コードが正しくありません。');
    }

    // 認証メール再送信
    public function resend(Request $request)
    {
        $user = $this->getUserFromSession();

        if ($user === null) {
            return back()->with('error', 'ユーザー情報が見つかりません。再度ログインしてください。');
        }

        if ($user->hasVerifiedEmail()) {
            return back()->with('error', 'このメールアドレスは既に認証済みです。');
        }

        $user->sendEmailVerificationNotification();
        return back()->with('resent', true);
    }

    // メール認証リンク処理（既存）
    public function verify(Request $request)
    {
        $user = \App\Models\User::findOrFail($request->route('id'));
        
        if (hash_equals((string) $request->route('hash'), sha1($user->getEmailForVerification())) === false) {
            throw new \Illuminate\Auth\Access\AuthorizationException;
        }

        if ($user->hasVerifiedEmail()) {
            return $this->redirectAuthenticatedUser($user);
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        Auth::login($user);
        return $this->redirectAuthenticatedUser($user)->with('verified', true);
    }

    private function getUserFromSession()
    {
        $userId = session('pending_verification_user_id');
        return $userId ? \App\Models\User::find($userId) : null;
    }

    private function redirectAuthenticatedUser($user)
    {
        Auth::login($user);
        return redirect()->route($user->hasCompletedProfile() ? 'home' : 'mypage.profile');
    }
}