<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * プロフィール編集画面を表示
     */
    public function edit()
    {
        return view('profile.editProfile');
    }

    /**
     * プロフィール情報を更新
     */
    public function update(ProfileUpdateRequest $request)
    {
        $user = auth()->user();

        // ユーザー情報を更新
        $user->update([
            'name' => $request->name,
        ]);

        // プロフィール情報を更新
        $profileData = [
            'postal_code' => $request->postal_code,
            'address' => $request->address,
            'building' => $request->building,
        ];

        // プロフィール画像の処理
        if ($request->hasFile('profile_image')) {
            $image = $request->file('profile_image');
            $filename = time() . '_' . $image->getClientOriginalName();
            $path = $image->storeAs('profile_images', $filename, 'public');
            $profileData['profile_image'] = $path;
        }

        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            $profileData
        );

        // プロフィール設定完了後はホーム画面へ
        return redirect()->route('home')->with('success', 'プロフィールを更新しました');
    }
}
