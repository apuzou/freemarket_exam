<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $page = $request->get('page', 'sell');
        
        $soldItems = $user->items()->with('purchases')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        $purchasedItems = $user->purchases()->with('item')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('profile.mypage', [
            'user' => $user,
            'currentPage' => $page,
            'soldItems' => $soldItems,
            'purchasedItems' => $purchasedItems,
        ]);
    }

    public function edit()
    {
        return view('profile.editProfile');
    }

    public function update(ProfileUpdateRequest $request)
    {
        $user = auth()->user();

        $user->update([
            'name' => $request->name,
        ]);

        $profileData = [
            'postal_code' => $request->postal_code,
            'address' => $request->address,
            'building' => $request->building,
        ];

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

        return redirect()->route('home')->with('success', 'プロフィールを更新しました');
    }
}
