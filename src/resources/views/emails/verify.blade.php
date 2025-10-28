<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>メール認証</title>
</head>
<body>
    <h2>メール認証</h2>
    <p>認証コードは以下の通りです：</p>
    <h1 style="color: #007bff; font-size: 24px; letter-spacing: 2px;">{{ $verificationCode }}</h1>
    <p>このコードは10分間有効です。</p>
    <p>または以下のリンクから認証してください：</p>
    <a href="{{ $verificationUrl }}">認証する</a>
</body>
</html>
