<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'coachtechフリマサイト')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <!-- ヘッダー -->
    <header class="header">
        <!-- ロゴ -->
        <a href="{{ route('home') }}" class="header-logo">
            <img src="{{ asset('storage/img/logo.svg') }}" alt="COACHTECH">
        </a>

        <!-- 検索バー -->
        <form action="{{ route('search') }}" method="GET" class="header-search">
            <input type="text" name="keyword" placeholder="なにをお探しですか?" value="{{ request('keyword') }}">
        </form>

        <!-- ナビゲーション -->
        <div class="navigation-menu">
            @auth
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="navigation-link">ログアウト</button>
                </form>
                <form action="{{ route('mypage') }}" method="GET">
                    <button type="submit" class="navigation-link">マイページ</button>
                </form>
                <form action="{{ route('sell') }}" method="GET">
                    <button type="submit" class="navigation-button-primary">出品</button>
                </form>
            @else
                <form action="{{ route('login') }}" method="GET">
                    <button type="submit" class="navigation-link">ログイン</button>
                </form>
                <form action="{{ route('login') }}" method="GET">
                    <button type="submit" class="navigation-link">マイページ</button>
                </form>
                <form action="{{ route('login') }}" method="GET">
                    <button type="submit" class="navigation-button-primary">出品</button>
                </form>
            @endauth
        </div>
    </header>

    <!-- 成功メッセージ -->
    @if(session('success'))
        <div class="success-message">
            {{ session('success') }}
        </div>
    @endif

    <!-- メインコンテンツ -->
    <main class="main">
        @yield('content')
    </main>
</body>
</html>
