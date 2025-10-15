<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>会員登録</title>
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
            <h1 class="form-title">会員登録</h1>
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="form-field">
                    <label for="name" class="form-field-label">ユーザー名</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" class="form-field-input">
                    @error('name')
                        <span class="form-field-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-field">
                    <label for="email" class="form-field-label">メールアドレス</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" class="form-field-input">
                    @error('email')
                        <span class="form-field-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-field">
                    <label for="password" class="form-field-label">パスワード</label>
                    <input id="password" type="password" name="password" required autocomplete="new-password" class="form-field-input">
                    @error('password')
                        <span class="form-field-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-field">
                    <label for="password_confirmation" class="form-field-label">確認用パスワード</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" class="form-field-input">
                    @error('password_confirmation')
                        <span class="form-field-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-actions">
                    <button type="submit" class="button-submit">登録する</button>
                </div>

                <div class="form-links">
                    <a href="{{ route('login') }}">ログインはこちら</a>
                </div>
            </form>
        </div>
    </main>
</body>
</html>
