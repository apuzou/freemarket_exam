<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>メール認証</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <!-- ヘッダー -->
    <header class="header">
        <a href="{{ route('home') }}" class="header-logo">
            <img src="{{ asset('storage/img/logo.svg') }}" alt="COACHTECH">
        </a>
    </header>

    <!-- 成功メッセージ -->
    @if(session('resent'))
        <div class="success-message">認証メールを送信しました</div>
    @endif

    <!-- メインコンテンツ -->
    <main class="main">
        <div class="email-verification-notice">
            <p class="verification-message">登録していただいたメールアドレスに認証メールを送付しました。</p>
            <p class="verification-message">メール認証を完了してください。</p>

            <div class="verification-actions">
                <form method="GET" action="{{ route('verification.show-code') }}" class="verification-form">
                    <button type="submit" class="button-verification">認証はこちらから</button>
                </form>

                <form method="POST" action="{{ route('verification.resend') }}" class="resend-form">
                    @csrf
                    <button type="submit" class="resend-link">認証メールを再送する</button>
                </form>
            </div>
        </div>
    </main>
</body>
</html>
