<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>確認コード入力</title>
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
        <!-- 確認コード入力フォーム -->
        <div class="verify-code-container">
            <h1 class="verify-code-title">確認コード入力</h1>
            
            <p class="verify-code-description">メールに送信された6桁の確認コードを入力してください。</p>
            
            @if(session('error'))
                <div class="form-field-error" style="text-align: center; margin-bottom: var(--spacing-lg);">{{ session('error') }}</div>
            @endif

            <form method="POST" action="{{ route('verification.verify-code') }}">
                @csrf
                
                <!-- 確認コード入力 -->
                <div class="form-field">
                    <label for="code" class="form-field-label">6桁の確認コード</label>
                    <input id="code" type="text" name="code" class="form-field-input verify-code-input" placeholder="123456" value="{{ old('code') }}">
                    @error('code')
                        <span class="form-field-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="verify-code-actions">
                    <button type="submit" class="button-submit">認証する</button>
                </div>
            </form>

            <div class="verify-code-resend">
                <form method="POST" action="{{ route('verification.resend') }}">
                    @csrf
                    <button type="submit" class="resend-link">確認コードを再送する</button>
                </form>
            </div>
        </div>
    </main>
</body>
</html>
