<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Purchase;
use App\Http\Requests\PurchaseRequest;
use App\Http\Requests\AddressRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Illuminate\Support\Facades\Log;

class PurchaseController extends Controller
{
    // Stripe設定
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    // 購入手続き画面表示
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

    // Stripe決済セッション作成
    public function store(PurchaseRequest $request, Item $item)
    {
        $shippingAddress = session('shipping_address') ?? [
            'postal_code' => Auth::user()->profile->postal_code,
            'address' => Auth::user()->profile->address,
            'building' => Auth::user()->profile->building,
        ];

        try {
            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'jpy',
                        'product_data' => [
                            'name' => $item->name,
                        ],
                        'unit_amount' => $item->price,
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('purchase.success', $item) . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('purchase.cancel', $item),
                'metadata' => [
                    'item_id' => $item->id,
                    'user_id' => Auth::id(),
                    'payment_method' => $request->payment_method,
                    'postal_code' => $shippingAddress['postal_code'],
                    'address' => $shippingAddress['address'],
                    'building' => $shippingAddress['building'] ?? '',
                ],
            ]);

            session([
                'purchase_session_id' => $session->id,
                'purchase_item_id' => $item->id,
                'purchase_payment_method' => $request->payment_method,
            ]);

            return redirect($session->url);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', '決済処理中にエラーが発生しました。');
        }
    }

    // Stripe決済成功時の処理
    public function success(Request $request, Item $item)
    {
        try {
            $sessionId = $request->get('session_id');
            Log::info('Stripe決済成功処理開始', [
                'session_id' => $sessionId,
                'user_id' => Auth::id(),
                'item_id' => $item->id,
            ]);

            $session = Session::retrieve($sessionId);

            if ($session->payment_status === 'paid') {
                $shippingAddress = session('shipping_address') ?? [
                    'postal_code' => Auth::user()->profile->postal_code,
                    'address' => Auth::user()->profile->address,
                    'building' => Auth::user()->profile->building,
                ];

            $paymentMethodMap = [
                'credit_card' => 1,
                'convenience_store' => 2,
            ];
            $paymentMethod = $paymentMethodMap[session('purchase_payment_method')] ?? 1;

            $purchase = Purchase::create([
                'user_id' => Auth::id(),
                'item_id' => $item->id,
                'payment_method' => $paymentMethod,
                'postal_code' => $shippingAddress['postal_code'],
                'address' => $shippingAddress['address'],
                'building' => $shippingAddress['building'] ?? null,
                'purchased_at' => now(),
            ]);

            session()->forget('shipping_address');
            session()->forget('purchase_session_id');
            session()->forget('purchase_item_id');
            session()->forget('purchase_payment_method');

            return redirect()->route('mypage', ['page' => 'buy'])->with('success', '商品を購入しました。');
            }

            return redirect()->route('home')->with('error', '決済が完了しませんでした。');
        } catch (\Exception $e) {
            return redirect()->route('home')->with('error', '決済処理中にエラーが発生しました。');
        }
    }

    // Stripe決済キャンセル時の処理
    public function cancel(Item $item)
    {
        session()->forget('purchase_session_id');
        session()->forget('purchase_item_id');
        session()->forget('purchase_payment_method');

        return redirect()->route('purchase.create', $item)->with('error', '決済がキャンセルされました。');
    }

    // 配送先変更画面表示
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

    // 配送先変更処理
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
