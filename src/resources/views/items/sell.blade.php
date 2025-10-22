@extends('layouts.app')

@section('title', '商品の出品')

@section('content')
<div class="form-container">
    <h1 class="form-title">商品の出品</h1>

    <form method="POST" action="{{ route('sell.store') }}" enctype="multipart/form-data">
        @csrf
        <!-- 商品画像 -->
        <div class="form-field">
            <label class="form-field-label">商品画像</label>
            <div class="upload-area">
                <div class="upload-button-options">
                    <label for="product_image" class="upload-button">
                        <span>画像を選択する</span>
                        <input type="file" id="product_image" name="product_image" accept="image/*" class="upload-input-hidden">
                    </label>
                </div>
                <div class="upload-preview" id="imagePreview"><!-- 画像プレビューエリア --></div>
            </div>
            @error('product_image')
                <span class="form-field-error">{{ $message }}</span>
            @enderror
        </div>

        <!-- 商品の詳細 -->
        <div class="section">
            <p class="section-title">商品の詳細</p>

            <!-- カテゴリー -->
            <div class="form-field">
                <label class="form-field-label">カテゴリー</label>
                <div class="category-selection">
                    @foreach($categories as $category)
                        <label class="category-option">
                            <input type="checkbox" name="categories[]" value="{{ $category->id }}" class="category-checkbox">
                            <span class="category-text">{{ $category->name }}</span>
                        </label>
                    @endforeach
                </div>
                @error('categories')
                    <span class="form-field-error">{{ $message }}</span>
                @enderror
            </div>

            <!-- 商品の状態 -->
            <div class="form-field">
                <label class="form-field-label">商品の状態</label>
                <select name="condition" class="form-field-input">
                    <option value="">選択してください</option>
                    <option value="1">良好</option>
                    <option value="2">目立った傷や汚れなし</option>
                    <option value="3">やや傷や汚れあり</option>
                    <option value="4">状態が悪い</option>
                </select>
                @error('condition')
                    <span class="form-field-error">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- 商品名と説明 -->
        <div class="section">
            <p class="section-title">商品名と説明</p>

            <!-- 商品名 -->
            <div class="form-field">
                <label class="form-field-label">商品名</label>
                <input type="text" name="name" value="{{ old('name') }}" class="form-field-input">
                @error('name')
                    <span class="form-field-error">{{ $message }}</span>
                @enderror
            </div>

            <!-- ブランド名 -->
            <div class="form-field">
                <label class="form-field-label">ブランド名</label>
                <input type="text" name="brand" value="{{ old('brand') }}" class="form-field-input">
                @error('brand')
                    <span class="form-field-error">{{ $message }}</span>
                @enderror
            </div>

            <!-- 商品の説明 -->
            <div class="form-field">
                <label class="form-field-label">商品の説明</label>
                <textarea name="description" class="form-field-input" rows="5">{{ old('description') }}</textarea>
                @error('description')
                    <span class="form-field-error">{{ $message }}</span>
                @enderror
            </div>

            <!-- 販売価格 -->
            <div class="form-field">
                <label class="form-field-label">販売価格(税込)</label>
                <div class="input-price-wrapper">
                    <input type="text" name="price" value="{{ old('price') }}" class="input-price">
                </div>
                @error('price')
                    <span class="form-field-error">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- 出品ボタン -->
        <div class="form-actions">
            <button type="submit" class="button-submit">出品する</button>
        </div>
    </form>
</div>

<!-- 画像プレビュー用のJavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const productImageInput = document.getElementById('product_image');
    const uploadSection = document.querySelector('.upload-area');
    
    if (productImageInput && uploadSection) {
        productImageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('imagePreview');
                    preview.innerHTML = '<img src="' + e.target.result + '" alt="商品画像プレビュー" class="upload-preview-image">';
                    uploadSection.classList.add('upload-area-has-image');
                };
                reader.readAsDataURL(file);
            } else {
                const preview = document.getElementById('imagePreview');
                preview.innerHTML = '';
                uploadSection.classList.remove('upload-area-has-image');
            }
        });
    }
});
</script>
@endsection
