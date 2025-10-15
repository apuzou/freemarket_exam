<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログイン</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <!-- ヘッダー -->
    <header class="header">
        <div class="header-logo">
            <img src="{{ asset('storage/img/logo.svg') }}" alt="COACHTECH">
        </div>
    </header>

    <!-- メインコンテンツ -->
    <main class="main">
        <div class="form-container">
            <h1 class="form-title">ログイン</h1>
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-field">
                    <label for="email" class="form-field-label">メールアドレス</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" class="form-field-input">
                    @error('email')
                        <span class="form-field-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-field">
                    <label for="password" class="form-field-label">パスワード</label>
                    <input id="password" type="password" name="password" required autocomplete="current-password" class="form-field-input">
                    @error('password')
                        <span class="form-field-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-actions">
                    <button type="submit" class="button-submit">ログインする</button>
                </div>

                <div class="form-links">
                    <a href="{{ route('register') }}">会員登録はこちら</a>
                </div>
            </form>
        </div>
    </main>
</body>
</html>
