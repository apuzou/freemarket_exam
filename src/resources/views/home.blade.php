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
            <div class="card">
                @if($item->image_path)
                    <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->name }}" class="card__image">
                @else
                    <div class="card__image-placeholder">商品画像</div>
                @endif

                <p class="card__title">{{ $item->name }}</p>
                <p class="card__price">¥{{ number_format($item->price) }}</p>

                @if($item->purchases()->exists())
                    <span class="card__badge">Sold</span>
                @endif
            </div>
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
