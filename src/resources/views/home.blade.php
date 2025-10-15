@extends('layouts.app')

@section('title', $title)

@section('content')
    <!-- タブナビゲーション -->
    <div class="tabs">
        <div class="tab-list">
            <a href="{{ route('home') }}" class="tab {{ $tab !== 'mylist' ? 'active' : '' }}">
                おすすめ
            </a>
            <a href="{{ route('home', ['tab' => 'mylist']) }}" class="tab {{ $tab === 'mylist' ? 'active' : '' }}">
                マイリスト
            </a>
        </div>
    </div>

    <!-- 商品一覧 -->
    <div class="items">
        @forelse($items as $item)
            <div class="item">
                @if($item->image_path)
                    <img src="{{ asset('storage/' . $item->image_path) }}" 
                        alt="{{ $item->name }}">
                @else
                    <div class="placeholder">商品画像</div>
                @endif

                <h3>{{ $item->name }}</h3>
                <p>¥{{ number_format($item->price) }}</p>

                @if($item->purchases()->exists())
                    <span class="sold">Sold</span>
                @endif
            </div>
        @empty
            <div class="empty">
                <p>商品が見つかりませんでした。</p>
            </div>
        @endforelse
    </div>

    <!-- ページネーション -->
    @if($items->hasPages())
        <div class="pagination">
            {{ $items->links() }}
        </div>
    @endif
@endsection
