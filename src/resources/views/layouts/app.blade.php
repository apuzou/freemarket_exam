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
                <a href="#" class="navigation-link">マイページ</a>
                <a href="#" class="navigation-button-primary">出品</a>
            @else
                <a href="{{ route('login') }}" class="navigation-link">ログイン</a>
                <a href="#" class="navigation-link">マイページ</a>
                <a href="#" class="navigation-button-primary">出品</a>
            @endauth
        </div>
    </header>

    <!-- メインコンテンツ -->
    <main class="main">
        @yield('content')
    </main>
</body>
</html>
