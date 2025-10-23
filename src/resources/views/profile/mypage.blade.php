@extends('layouts.app')

@section('title', 'マイページ')

@section('content')
<div class="mypage-container">
    <!-- プロフィール情報セクション -->
    <div class="profile-info-section">
        <div class="profile-image-container">
            @if($user->profile && $user->profile->profile_image)
                <img src="{{ asset('storage/' . $user->profile->profile_image) }}" alt="プロフィール画像" class="profile-image">
            @else
                <div class="profile-image-placeholder"></div>
            @endif
        </div>

        <p class="username">{{ $user->name }}</p>

        <a href="{{ route('mypage.profile') }}" class="button-edit-profile">プロフィールを編集</a>
    </div>

    <!-- タブナビゲーション -->
    <div class="navigation-tabs">
        <div class="tab-list">
            <a href="{{ route('mypage', ['page' => 'sell']) }}" class="navigation-tab {{ $currentPage === 'sell' ? 'navigation-tab-active' : '' }}">
                出品した商品
            </a>
            <a href="{{ route('mypage', ['page' => 'buy']) }}" class="navigation-tab {{ $currentPage === 'buy' ? 'navigation-tab-active' : '' }}">
                購入した商品
            </a>
        </div>
    </div>

    <!-- 商品一覧 -->
    <div class="mypage-content">
        @if($currentPage === 'sell')
            @if($soldItems->isEmpty())
                <p class="empty-state">商品が見つかりませんでした</p>
            @else
                <div class="card-grid">
                    @foreach($soldItems as $item)
                        <a href="{{ route('item.show', $item) }}" class="card card-link">
                            @if($item->image_path && $item->image_path !== '')
                                <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->name }}" class="card-image">
                            @else
                                <div class="card-image-placeholder">商品画像</div>
                            @endif

                            <p class="card-title">{{ $item->name }}</p>

                            <p class="card-price">¥{{ number_format($item->price) }}</p>

                            @if($item->purchases()->exists())
                                <span class="card-badge">Sold</span>
                            @endif
                        </a>
                    @endforeach
                </div>
                
                <!-- ページネーション -->
                @if($soldItems->hasPages())
                    {{ $soldItems->links('pagination.simple') }}
                @endif
            @endif
        @else
            @if($purchasedItems->isEmpty())
                <p class="empty-state">商品が見つかりませんでした</p>
            @else
                <div class="card-grid">
                    @foreach($purchasedItems as $purchase)
                        <a href="{{ route('item.show', $purchase->item) }}" class="card card-link">
                            @if($purchase->item->image_path && $purchase->item->image_path !== '')
                                <img src="{{ asset('storage/' . $purchase->item->image_path) }}" alt="{{ $purchase->item->name }}" class="card-image">
                            @else
                                <div class="card-image-placeholder">商品画像</div>
                            @endif

                            <p class="card-title">{{ $purchase->item->name }}</p>

                            <p class="card-price">¥{{ number_format($purchase->item->price) }}</p>
                        </a>
                    @endforeach
                </div>
                
                <!-- ページネーション -->
                @if($purchasedItems->hasPages())
                    {{ $purchasedItems->links('pagination.simple') }}
                @endif
            @endif
        @endif
    </div>
</div>
@endsection
