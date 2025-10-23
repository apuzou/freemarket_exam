<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Verified;

class VerificationController extends Controller
{
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

    public function notice()
    {
        if (Auth::check() && Auth::user()->hasVerifiedEmail()) {
            return $this->redirectAuthenticatedUser(Auth::user());
        }

        return view('auth.email-verification-notice');
    }

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

    public function check(Request $request)
    {
        $user = $this->getUserFromSession();
        
        if ($user === null) {
            return back()->with('error', 'ユーザー情報が見つかりません。再度ログインしてください。');
        }

        if ($user->hasVerifiedEmail()) {
            return $this->redirectAuthenticatedUser($user);
        }

        $user->sendEmailVerificationNotification();
        return back()->with('resent', true);
    }

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
}