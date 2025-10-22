@extends('layouts.app')

@section('title', 'プロフィール設定')

@section('content')
<div class="profile-container">
    <h1 class="form-title">プロフィール設定</h1>
    <form method="POST" action="{{ route('mypage.profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PATCH')
        <!-- プロフィール画像 -->
        <div class="profile-section">
            <div class="profile-image-display">
                @if(auth()->user()->profile && auth()->user()->profile->profile_image)
                    <img src="{{ asset('storage/' . auth()->user()->profile->profile_image) }}" alt="プロフィール画像" class="profile-image" id="profile-image-preview">
                @else
                    <div class="profile-image-placeholder" id="profile-image-preview"></div>
                @endif
            </div>
            <label for="profile_image" class="upload-button">
                <span>画像を選択する</span>
                <input type="file" id="profile_image" name="profile_image" accept="image/*" class="upload-input-hidden">
            </label>
            @error('profile_image')
                <span class="form-field-error">{{ $message }}</span>
            @enderror
        </div>

        <!-- 入力フィールド -->
        <div class="form-field">
            <label for="name" class="form-field-label">ユーザー名</label>
            <input id="name" type="text" name="name" value="{{ old('name', auth()->user()->name) }}" class="form-field-input">
            @error('name')
                <span class="form-field-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-field">
            <label for="postal_code" class="form-field-label">郵便番号</label>
            <input id="postal_code" type="text" name="postal_code" value="{{ old('postal_code', auth()->user()->profile?->postal_code) }}" placeholder="郵便番号を入力してください" class="form-field-input">
            @error('postal_code')
                <span class="form-field-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-field">
            <label for="address" class="form-field-label">住所</label>
            <input id="address" type="text" name="address" value="{{ old('address', auth()->user()->profile?->address) }}" placeholder="住所を入力してください" class="form-field-input">
            @error('address')
                <span class="form-field-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-field">
            <label for="building" class="form-field-label">建物名</label>
            <input id="building" type="text" name="building" value="{{ old('building', auth()->user()->profile?->building) }}" class="form-field-input">
            @error('building')
                <span class="form-field-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-actions">
            <button type="submit" class="button-submit">更新する</button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('profile_image');
    const previewContainer = document.getElementById('profile-image-preview');

    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();

            reader.onload = function(e) {
                if (previewContainer.tagName === 'IMG') {
                    previewContainer.src = e.target.result;
                } else {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.alt = 'プロフィール画像プレビュー';
                    img.className = 'profile-image';
                    img.id = 'profile-image-preview';

                    previewContainer.parentNode.replaceChild(img, previewContainer);
                }
            };

            reader.readAsDataURL(file);
        }
    });
});
</script>
@endsection