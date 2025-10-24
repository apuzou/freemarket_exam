<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class PurchaseRequest extends FormRequest
{
    public function authorize()
    {
        return Auth::check();
    }

    public function rules()
    {
        return [
            'payment_method' => ['required', 'string', 'in:credit_card,convenience_store'],
        ];
    }

    public function messages()
    {
        return [
            'payment_method.required' => '支払い方法を選択してください。',
            'payment_method.in' => '無効な支払い方法が選択されました。',
        ];
    }

    protected function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $shippingAddress = session('shipping_address') ?? [
                'postal_code' => Auth::user()->profile->postal_code ?? '',
                'address' => Auth::user()->profile->address ?? '',
            ];

            if (empty($shippingAddress['postal_code']) || empty($shippingAddress['address'])) {
                $validator->errors()->add('shipping_address', '配送先が設定されていません。住所変更ボタンから設定してください。');
            }
        });
    }
}
