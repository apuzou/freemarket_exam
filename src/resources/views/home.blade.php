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
    <div class="card-grid">
        @forelse($items as $item)
            <a href="{{ route('item.show', $item) }}" class="card card-link">
                @if($item->image_path)
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
        @empty
                <p class="empty-state">商品が見つかりませんでした。</p>
        @endforelse
    </div>

    <!-- ページネーション -->
    @if($items->hasPages())
        <div class="pagination-container">
            {{ $items->links() }}
        </div>
    @endif
@endsection
