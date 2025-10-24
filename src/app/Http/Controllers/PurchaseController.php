<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Purchase;
use App\Http\Requests\PurchaseRequest;
use App\Http\Requests\AddressRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    public function create(Item $item)
    {
        $user = Auth::user();
        
        $shippingAddress = session('shipping_address') ?? [
            'postal_code' => $user->profile->postal_code ?? '',
            'address' => $user->profile->address ?? '',
            'building' => $user->profile->building ?? '',
        ];

        return view('purchase.purchase', compact('item', 'shippingAddress'));
    }

    public function store(PurchaseRequest $request, Item $item)
    {
        $shippingAddress = session('shipping_address') ?? [
            'postal_code' => Auth::user()->profile->postal_code,
            'address' => Auth::user()->profile->address,
            'building' => Auth::user()->profile->building,
        ];

        Purchase::create([
            'user_id' => Auth::id(),
            'item_id' => $item->id,
            'payment_method' => $request->payment_method,
            'postal_code' => $shippingAddress['postal_code'],
            'address' => $shippingAddress['address'],
            'building' => $shippingAddress['building'],
        ]);

        session()->forget('shipping_address');

        return redirect()->route('home')->with('success', '商品を購入しました。');
    }

    public function editAddress(Item $item)
    {
        $user = Auth::user();
        
        $currentAddress = session('shipping_address') ?? [
            'postal_code' => $user->profile->postal_code ?? '',
            'address' => $user->profile->address ?? '',
            'building' => $user->profile->building ?? '',
        ];

        return view('purchase.edit_address', compact('item', 'currentAddress'));
    }

    public function updateAddress(AddressRequest $request, Item $item)
    {
        session([
            'shipping_address' => [
                'postal_code' => $request->postal_code,
                'address' => $request->address,
                'building' => $request->building,
            ]
        ]);

        return redirect()->route('purchase.create', $item)->with('success', '配送先を更新しました。');
    }
}
