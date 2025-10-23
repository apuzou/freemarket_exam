# COACHTECH フリマサイト

## 環境構築

### 必要な環境

- Docker
- Docker Compose

### Docker ビルド

```bash
git clone <git@github.com:apuzou/freemarket_exam.git>
cd freemarket_exam
docker-compose up -d --build
```

### Laravel 環境構築

```bash
# PHPコンテナに入る
docker-compose exec php bash

# Composerで依存関係をインストール
composer install

# 環境変数ファイルの作成
cp .env.example .env

# アプリケーションキーの生成
php artisan key:generate

# データベースマイグレーション
php artisan migrate

# データベースシーディング
php artisan db:seed
```

### 環境変数設定

`.env` ファイルで以下の設定を確認してください：

```env
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel_db
DB_USERNAME=root
DB_PASSWORD=password

MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
```

## 使用技術(実行環境)

- PHP 8.1+
- Laravel 8.x
- MySQL 8.0.26
- nginx 1.21.1
- Docker Compose
- Laravel Fortify カスタム認証システム
- Mailhog (メール認証・テスト)

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
- 商品出品: `http://localhost/sell`
- マイページ: `http://localhost/mypage`
- phpMyAdmin: `http://localhost:8080/`
- Mailhog Web UI: `http://localhost:8025/`

## 主要機能

- ユーザー認証（登録・ログイン・ログアウト）
- メール認証機能
- 商品一覧表示・検索
- 商品詳細表示
- 商品出品機能
- お気に入り機能
- コメント機能
- プロフィール管理

## トラブルシューティング

1. **Docker コンテナが起動しない**

   ```bash
   docker-compose down
   docker-compose up -d --build
   ```

2. **データベース接続エラー**

   - `.env` ファイルの `DB_HOST=mysql` を確認
   - MySQL コンテナが起動しているか確認

3. **メール送信ができない**

   - Mailhog コンテナが起動しているか確認
   - `.env` の `MAIL_HOST=mailhog` を確認

4. **画像が表示されない**

   ```bash
   php artisan storage:link
   ```

5. **キャッシュクリア**
   ```bash
   php artisan view:clear
   php artisan config:clear
   php artisan cache:clear
   ```

## 開発時の注意点

- 画像ファイルは `storage/app/public/images/` に保存されます
- プロフィール画像は `storage/app/public/profile_images/` に保存されます
- メール認証は Mailhog を使用してローカル環境でテストできます
