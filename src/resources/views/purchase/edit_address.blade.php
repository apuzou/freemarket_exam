@extends('layouts.app')

@section('title', '配送先変更')

@section('content')
<div class="edit-address-container">
    <h1 class="form-title">住所の変更</h1>

    <form action="{{ route('purchase.address.update', $item) }}" method="POST">
        @csrf
        @method('PATCH')

        <div class="form-edit-address">
            <label for="postal_code" class="form-field-label">郵便番号</label>
            <input type="text" id="postal_code" name="postal_code" class="form-field-input" value="{{ old('postal_code', $currentAddress['postal_code']) }}">
            @error('postal_code')
                <p class="error-message">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-edit-address">
            <label for="address" class="form-field-label">住所</label>
            <input type="text" id="address" name="address" class="form-field-input" value="{{ old('address', $currentAddress['address']) }}">
            @error('address')
                <p class="error-message">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-edit-address">
            <label for="building" class="form-field-label">建物名</label>
            <input type="text" id="building" name="building" class="form-field-input" value="{{ old('building', $currentAddress['building']) }}">
            @error('building')
                <p class="error-message">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-actions">
            <button type="submit" class="button-submit">更新する</button>
        </div>
    </form>
</div>
@endsection
