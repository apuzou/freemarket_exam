<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * マイページ（プロフィール画面）を表示
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $page = $request->get('page', 'sell'); // デフォルトは出品商品
        
        // 出品した商品一覧
        $soldItems = collect(); // 現在は空のコレクション
        // 将来的に: $soldItems = $user->items()->with('purchases')->get();
        
        // 購入した商品一覧  
        $purchasedItems = collect(); // 現在は空のコレクション
        // 将来的に: $purchasedItems = $user->purchases()->with('item')->get();
        
        return view('profile.mypage', [
            'user' => $user,
            'currentPage' => $page,
            'soldItems' => $soldItems,
            'purchasedItems' => $purchasedItems,
        ]);
    }

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
