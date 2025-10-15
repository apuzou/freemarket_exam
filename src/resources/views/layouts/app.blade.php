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
        <a href="{{ route('home') }}" class="logo">
            <img src="{{ asset('storage/img/logo.svg') }}" alt="COACHTECH">
        </a>

        <!-- 検索バー -->
        <form action="{{ route('search') }}" method="GET" class="search">
            <input type="text" name="keyword" placeholder="なにをお探しですか?" value="{{ request('keyword') }}">
        </form>

        <!-- ナビゲーション -->
        <div class="navigation">
            @auth
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="nav-link">ログアウト</button>
                </form>
                <a href="#" class="nav-link">マイページ</a>
                <a href="#" class="primary-button">出品</a>
            @else
                <a href="{{ route('login') }}" class="nav-link">ログイン</a>
                <a href="#" class="nav-link">マイページ</a>
                <a href="#" class="primary-button">出品</a>
            @endauth
        </div>
    </header>

    <!-- メインコンテンツ -->
    <main class="main">
        @yield('content')
    </main>
</body>
</html>
