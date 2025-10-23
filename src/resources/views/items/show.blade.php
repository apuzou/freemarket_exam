@extends('layouts.app')

@section('title', $item->name)

@section('content')
<div class="item-detail-container">
    <div class="item-detail-section">
        <!-- 商品画像 -->
        <div class="item-image-section">
            @if($item->image_path && $item->image_path !== '')
                <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->name }}" class="card-image item-detail-image">
            @else
                <div class="card-image-placeholder item-detail-image">商品画像</div>
            @endif
        </div>

        <!-- 商品詳細情報 -->
        <div class="item-info-section">
            <!-- 商品名 -->
            <h1 class="item-name">{{ $item->name }}</h1>

            <!-- ブランド -->
            <p class="item-brand">{{ $item->brand }}</p>

            <!-- 価格 -->
            <p class="item-price">{{ number_format($item->price) }}</p>

            <!-- お気に入り・コメント数 -->
            <div class="interaction-container">
                <div class="interaction-item">
                    @auth
                        <form method="POST" action="{{ route('item.like', $item) }}" class="like-form">
                            @csrf
                            <button type="submit" class="like-button {{ $isLiked ? 'liked' : '' }}">
                                <svg class="like-icon" fill="{{ $isLiked ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                </svg>
                                <span class="like-count">{{ $likeCount }}</span>
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="like-button">
                            <svg class="like-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                            </svg>
                            <span class="like-count">{{ $likeCount }}</span>
                        </a>
                    @endauth
                </div>

                <div class="interaction-item">
                    <div class="comment-count">
                        <svg class="comment-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        <span class="comment-count-number">{{ $commentCount }}</span>
                    </div>
                </div>
            </div>

            <!-- 購入ボタン -->
            <div class="button-purchase">
                <button type="submit" class="button-submit">購入手続きへ</button>
            </div>

            <!-- 商品説明 -->
            <div class="form-field">
                <p class="section-title">商品説明</p>
                <div class="item-description">{{ $item->description }}</div>
            </div>

            <!-- 商品カテゴリ・状態 -->
            <div class="form-field">
                <p class="section-title">商品の情報</p>
                <div class="item-details">
                    <span class="form-field-label">カテゴリー</span>
                    <div class="category-tags">
                        @foreach($item->categories as $category)
                            <span class="badge-category">{{ $category->name }}</span>
                        @endforeach
                    </div>
                </div>
                <div class="item-details">
                    <span class="form-field-label">商品の状態</span>
                    <span class="detail-value">{{ $item->condition_text }}</span>
                </div>
            </div>

            <!-- コメント -->
            <div class="form-field">
                <p class="section-title">コメント({{ $commentCount }})</p>
                @if($item->comments->count() > 0)
                    <div>
                        @foreach($item->comments as $comment)
                            <div class="comment-item">
                                <div class="comment-user">
                                    <div class="user-avatar">
                                        @if($comment->user->profile && $comment->user->profile->profile_image)
                                            <img src="{{ asset('storage/' . $comment->user->profile->profile_image) }}" alt="{{ $comment->user->name }}" class="profile-image">
                                        @else
                                            <div class="profile-image-placeholder"></div>
                                        @endif
                                    </div>
                                    <div class="user-name">
                                        <span>{{ $comment->user->name }}</span>
                                    </div>
                                </div>
                                <div class="comment-content">{{ $comment->comment }}</div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="empty-state">コメントはまだありません。</p>
                @endif

                <!-- コメント入力フォーム -->
                @auth
                    <form method="POST" action="{{ route('item.comment', $item) }}" class="comment-form">
                        @csrf
                        <p class="form-field-label">商品へのコメント</p>
                        <div class="form-field">
                            <textarea name="comment" class="form-field-input" rows="5" placeholder="コメントを入力してください"></textarea>
                            @error('comment')
                                <span class="form-field-error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="button-submit">コメントを送信する</button>
                        </div>
                    </form>
                @else
                    <div class="comment-login-required">
                        <p>コメントするには<a href="{{ route('login') }}">ログイン</a>が必要です。</p>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</div>
@endsection
