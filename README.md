# COACHTECHフリマサイト

## 環境構築

### 必要な環境

- Docker
- Docker Compose

### Docker ビルド

```bash
git clone <git@github.com:apuzou/freemarket_exam.git>
docker-compose up -d --build
```

### Laravel 環境構築

```bash
# PHPコンテナに入る
docker-compose exec php bash

# Composerで依存関係をインストール
composer install

# 環境変数ファイルの作成（必要に応じて設定を変更）
cp .env.example .env

# アプリケーションキーの生成
php artisan key:generate

# データベースマイグレーション
php artisan migrate

# データベースシーディング
php artisan db:seed
```

## 使用技術(実行環境)

- PHP 8.1+
- Laravel 8.x
- MySQL 8.0.26
- nginx 1.21.1
- Docker Compose
- Laravel Fortify (認証)

## データベース構造

- users（ユーザー）
- items（商品）
- categories（カテゴリー）
- item_categories（商品-カテゴリー中間テーブル）
- likes（いいね）
- comments（コメント）
- purchases（購入履歴）
- profiles（プロフィール）

## ER 図

<img width="1200" height="718" alt="freemarket_exam_er" src="https://github.com/user-attachments/assets/0eef632b-6844-4a71-8829-90c9b67130b1" />

## URL

- フリマサイト: `http://localhost/`
- ユーザー登録: `http://localhost/register`
- ログイン: `http://localhost/login`
- 商品検索: `http://localhost/search`
- phpMyAdmin: `http://localhost:8080/`

