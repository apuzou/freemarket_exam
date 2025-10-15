@extends('layouts.app')

@section('title', $title)

@section('content')
    <!-- タブナビゲーション -->
    <div class="navigation-tabs">
        <div class="tab-list">
            <a href="{{ route('home') }}" class="navigation-tab {{ $tab !== 'mylist' ? 'navigation-tab-active' : '' }}">
                おすすめ
            </a>
            <a href="{{ route('home', ['tab' => 'mylist']) }}" class="navigation-tab {{ $tab === 'mylist' ? 'navigation-tab-active' : '' }}">
                マイリスト
            </a>
        </div>
    </div>

    <!-- 商品一覧 -->
    <div class="product-grid">
        @forelse($items as $item)
            <div class="product-card">
                @if($item->image_path)
                    <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->name }}" class="product-image">
                @else
                    <div class="product-image-placeholder">商品画像</div>
                @endif

                <h3 class="product-title">{{ $item->name }}</h3>
                <p class="product-price">¥{{ number_format($item->price) }}</p>

                @if($item->purchases()->exists())
                    <span class="product-sold-badge">Sold</span>
                @endif
            </div>
        @empty
            <div class="empty-state">
                <p>商品が見つかりませんでした。</p>
            </div>
        @endforelse
    </div>

    <!-- ページネーション -->
    @if($items->hasPages())
        <div class="pagination-container">
            {{ $items->links() }}
        </div>
    @endif
@endsection
