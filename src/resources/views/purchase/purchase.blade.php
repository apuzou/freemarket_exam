@extends('layouts.app')

@section('title', '購入')

@section('content')
<div class="purchase-container">
    <div class="purchase-grid">
        <!-- 左側カラム -->
        <div class="purchase-left">
            <div class="purchase-item-details">
                <div>
                    @if($item->image_path && $item->image_path !== '')
                        <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->name }}" class="purchase-item-image">
                    @else
                        <div class="card-image-placeholder purchase-item-image">商品画像</div>
                    @endif
                </div>
                <div class="purchase-item-info">
                    <p class="item-name">{{ $item->name }}</p>
                    <p class="item-price">{{ number_format($item->price) }}</p>
                </div>
            </div>

            <div class="border-bottom"></div>

            <form action="{{ route('purchase.store', $item) }}" method="POST" class="purchase-form" id="purchase-form">
                @csrf

                <div class="section">
                    <h3 class="form-section-title">支払い方法</h3>
                    <div class="form-group">
                        <select name="payment_method" class="form-field-input payment-select">
                            <option value="">選択してください</option>
                            <option value="credit_card">クレジットカード支払い</option>
                            <option value="convenience_store">コンビニ支払い</option>
                        </select>
                        @error('payment_method')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="border-bottom"></div>

                <div class="section">
                    <h3 class="form-section-title">配送先</h3>
                    <div class="form-group">
                        <p class="address-text">〒{{ $shippingAddress['postal_code'] }}</p>
                        <p class="address-text">{{ $shippingAddress['address'] }}</p>
                        @if($shippingAddress['building'])
                            <p class="address-text">{{ $shippingAddress['building'] }}</p>
                        @endif
                    </div>
                    <a href="{{ route('purchase.address.edit', $item) }}" class="button-address-change">変更する</a>
                </div>

                <div class="border-bottom"></div>
            </form>
        </div>

        <!-- 右側カラム -->
        <div class="purchase-right">
            <div class="purchase-summary">
                <div class="summary-item">
                    <span class="summary-label">商品代金</span>
                    <span class="summary-value-price">¥{{ number_format($item->price) }}</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">支払い方法</span>
                    <span class="summary-value-payment" id="selected-payment-method">-</span>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" form="purchase-form" class="button-submit">購入する</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const paymentSelect = document.querySelector('select[name="payment_method"]');
    const paymentMethodDisplay = document.getElementById('selected-payment-method');
    if (paymentSelect && paymentMethodDisplay) {
        paymentSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption.value === '') {
                paymentMethodDisplay.textContent = '選択してください';
            } else {
                paymentMethodDisplay.textContent = selectedOption.textContent;
            }
        });
    }
});
</script>
@endsection
