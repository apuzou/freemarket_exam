# COACHTECH フリマサイト

## プロジェクト概要

Laravel 8.x で構築されたフリマサイトです。ユーザー認証、商品管理、いいね機能、コメント機能、決済機能（Stripe）を実装しています。

## 環境構築

### 必要な環境

- Docker
- Docker Compose
- Git

### セットアップ手順

```bash
# リポジトリのクローン
git clone git@github.com:apuzou/freemarket_exam.git
cd freemarket_exam

# Dockerコンテナのビルドと起動
docker-compose up -d --build

# PHPコンテナに入る
docker-compose exec php bash

# Composerで依存関係をインストール
composer install

# 環境変数ファイルの作成
cp .env.example .env

# アプリケーションキーの生成
php artisan key:generate

# ストレージリンクの作成（画像表示用）
php artisan storage:link

# データベースマイグレーション
php artisan migrate

# データベースシーディング（サンプルデータの投入）
php artisan db:seed
```

### 環境変数設定

`.env` ファイルで以下の設定を確認・設定してください：

```env
# アプリケーション設定
APP_NAME=Laravel
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

# データベース設定
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel_db
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_pass

# メール設定（Mailhog使用）
MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=CT.freemarket@example.com
MAIL_FROM_NAME="${APP_NAME}"

# Stripe決済設定
STRIPE_KEY=pk_test_your_stripe_public_key
STRIPE_SECRET=sk_test_your_stripe_secret_key
```

## 使用技術

### バックエンド

- PHP 8.1+
- Laravel 8.x
- MySQL 8.0.26

### インフラ

- Docker Compose
- nginx 1.21.1
- Mailhog (メールテスト用)

### 認証・決済

- Laravel Fortify カスタム認証システム
- Stripe 決済システム

## データベース構造

以下のテーブルが存在します：

- `users` - ユーザー情報
- `profiles` - プロフィール情報
- `items` - 商品情報
- `categories` - カテゴリ情報
- `item_categories` - 商品-カテゴリ中間テーブル
- `likes` - いいね情報
- `comments` - コメント情報
- `purchases` - 購入履歴

## ER 図

<img width="2861" height="1511" alt="freemarket_er" src="https://github.com/user-attachments/assets/9b858456-c5f1-4405-a12f-c20b00567815" />

## 主要機能

### 認証機能

- ユーザー登録
- ログイン/ログアウト
- メール認証（Mailhog 使用）

### 商品管理

- 商品一覧表示
- 商品検索（部分一致）
- 商品詳細表示
- 商品出品
- マイリスト表示

### インタラクション

- いいね機能
- コメント機能

### プロフィール管理

- プロフィール画像の設定
- 住所情報の管理
- 出品商品一覧
- 購入商品一覧

### 決済機能

- Stripe 決済統合
- 配送先変更

## アクセス URL

### アプリケーション

- フリマサイト: `http://localhost/`
- ユーザー登録: `http://localhost/register`
- ログイン: `http://localhost/login`
- 商品検索: `http://localhost/search`
- 商品出品: `http://localhost/sell`
- マイページ: `http://localhost/mypage`

### 管理ツール

- phpMyAdmin: `http://localhost:8080/` (ユーザー: laravel_user / パスワード: laravel_pass)
- Mailhog Web UI: `http://localhost:8025/`

## テスト

### テストの実行

```bash
# 全テストの実行
docker-compose exec php vendor/bin/phpunit

# 特定のテストクラスの実行
docker-compose exec php vendor/bin/phpunit tests/Feature/MemberRegistrationTest.php

# 特定のテストメソッドの実行
docker-compose exec php vendor/bin/phpunit --filter test_successful_registration
```

### テストカバレッジ

以下の機能について 40 件のテストケースが用意されています：

- 会員登録機能（6 件）
- ログイン機能（4 件）
- ログアウト機能（1 件）
- 商品一覧取得（3 件）
- マイリスト機能（3 件）
- 商品検索機能（2 件）
- 商品詳細表示（2 件）
- いいね機能（3 件）
- コメント機能（4 件）
- 商品購入機能（3 件）
- 支払い方法選択（1 件）
- 配送先変更（2 件）
- ユーザー情報取得（1 件）
- ユーザー情報変更（1 件）
- 商品出品機能（1 件）
- メール認証機能（3 件）

## トラブルシューティング

### Docker コンテナが起動しない

```bash
docker-compose down
docker-compose up -d --build
```

#### Apple Silicon(M1/M2)でイメージ起動に問題が出る場合

一部の公式イメージが arm64 で不安定な場合は、該当サービスに一時的に次の指定を追加してください。

```yml
platform: linux/amd64
```

### データベース接続エラー

- `.env` ファイルの `DB_HOST=mysql` を確認
- MySQL コンテナが起動しているか確認
- コンテナ名を確認: `docker-compose ps`

### メール送信ができない

- Mailhog コンテナが起動しているか確認
- `.env` の `MAIL_HOST=mailhog` を確認
- Mailhog Web UI (`http://localhost:8025/`) でメールを受信確認

### 画像が表示されない

```bash
php artisan storage:link
```

### キャッシュクリア

```bash
php artisan view:clear
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan optimize:clear
```

### Stripe 決済のテスト

- テスト用 API キーを `.env` に設定
- Stripe テストカード: `4242 4242 4242 4242`
- 有効期限: 任意の未来の日付
- CVC: 任意の 3 桁

## 開発時の注意点

### ファイル保存場所

- 商品画像: `storage/app/public/images/`
- プロフィール画像: `storage/app/public/profile_images/`
- ロゴ画像: `storage/app/public/img/`

### メール認証

- Mailhog を使用してローカル環境でテスト可能
- `http://localhost:8025/` でメールの確認が可能

### セッション管理

- 配送先住所などはセッションで管理
- 購入完了後にセッションをクリア

### データベース操作

```bash
# マイグレーションのリセット
php artisan migrate:refresh --seed

# 特定のテーブルをリセット
php artisan migrate:refresh --path=database/migrations/2025_10_14_000000_create_items_table.php
```
