# Laravel業務API構築プロジェクト（注文管理システム）

## 概要

このプロジェクトは、**Laravel × Docker構成**で構築した業務用APIサーバーのポートフォリオです。  
「**注文 → 在庫管理 (→ キャンセル) → 出荷 → 通知（メール）**」という一連の業務フローを再現し、  
**ドメイン設計・トランザクション・メール通知・テスト設計**などの実務を想定した設計を意識しています。

---

## 現在の実装状況（2025/9/14時点）

- [x] Docker による Laravel 開発環境構築（MySQL / Nginx / PHP-FPM）
- [x] 注文処理（在庫を引く）＋トランザクション管理
- [x] ステータス更新（キャンセル時は在庫を戻す）
- [x] 出荷処理（出荷情報登録 + 出荷通知メール送信）
- [x] 統合テスト（注文作成／キャンセルによる在庫復元／出荷登録と通知メール送信）
- [x] 認証機能（Sanctumによるトークン認証）
- [x] Postman Collection で API 実行確認（`order_id`, `token` など自動セット済）

---

## 使用技術

| 項目         | 内容                                   |
|--------------|----------------------------------------|
| フレームワーク | Laravel 12 + PHP 8.2                  |
| DB            | MySQL（Dockerコンテナ）                |
| コンテナ管理   | Docker + docker-compose               |
| Webサーバー   | Nginx + PHP-FPM                        |
| 認証          | Laravel Sanctum                        |
| テスト        | PHPUnit（FeatureTest / MailFake）     |
| API確認       | Postman Collection                     |
| 今後追加予定   | GitHub Actions / AWS EC2 / OpenAPI etc |

---

## API設計（実装済）

| メソッド | エンドポイント                       | 説明                          |
|----------|--------------------------------------|-------------------------------|
| POST     | `/api/auth/login`                   | ログイン（Sanctumトークン取得） |
| POST     | `/api/auth/logout`                  | ログアウト（トークン無効化）    |
| POST     | `/api/orders`                       | 新規注文作成（在庫を引く）     |
| PUT      | `/api/orders/{order}/status`        | ステータス変更（例: キャンセル）|
| POST     | `/api/orders/{order}/shipments`     | 出荷処理（メール送信あり）     |
| GET      | `/api/orders`                       | 注文一覧（絞り込み対応予定）   |
| GET      | `/api/orders/{order}`               | 注文詳細の取得                 |

---

## ディレクトリ構成（抜粋）

```
laravel-order-api/
├── app/
│   ├── Enums/              # Enum定義（注文ステータス等）
│   ├── Http/
│   │   ├── Controllers/    # APIコントローラ（Auth含む）
│   │   ├── Requests/       # リクエストバリデーション
│   │   └── Resources/      # APIリソース（レスポンス整形）
│   ├── Mail/               # 通知メールクラス
│   ├── Models/             # Eloquentモデル
│   ├── Providers/          # サービスプロバイダ
│   ├── Repositories/       # データ取得・永続化処理
│   └── Services/           # 業務ロジック層
├── database/
│   ├── factories/          # ダミーデータ生成
│   ├── migrations/         # マイグレーション
│   └── seeders/            # 初期データ投入
├── docker/
│   ├── nginx/              # Nginx 設定
│   └── php/                # PHP 設定
├── docs/                   # API仕様書・Postmanコレクション
├── resources/
│   └── views/
│       └── emails/         # 通知メールBladeテンプレート
├── routes/                 # ルーティング定義
├── tests/
│   ├── Feature/            # 統合テスト
│   └── Unit/               # 単体テスト（今後追加予定）
├── .env.example            # 環境変数サンプル
├── docker-compose.yml      # コンテナ構成
├── composer.json           # PHP依存定義
└── README.md
```

---

## 起動方法（ローカル開発）

### 1. `.env` 設定
```bash
cp .env.example .env
```

### 2. Dockerコンテナ起動
```bash
docker-compose up -d --build
```

### 3. Laravel 初期セットアップ
```bash
docker exec -it laravel-app php artisan key:generate
docker exec -it laravel-app php artisan migrate:fresh --seed
```

> 起動後、`http://localhost/api/orders` にアクセスするとAPIレスポンスを確認できます。

---

## Postman でのAPI確認

- Postmanコレクション（`docs\laravel-order-api-postman-collection.json`）を同梱
- 主要API（ログイン／注文／キャンセル／出荷）を実行可能
- ベースURLやトークンは環境変数（`{{base_url}}`, `{{token}}`）で自動管理

---

## テスト

Laravel標準のPHPUnitを使って、注文～出荷までの一連の業務フローをE2Eでテストしています。

```bash
docker exec -it laravel-app php artisan test
```

---

## 今後行いたいこと

- 単体テスト（UnitTest）の追加
- APIサーバー専用化のための不要リソース整理
- CI/CD（GitHub Actions）による自動テスト・デプロイ
- AWS上での本番構成（Route53による独自ドメイン設定 + SSL対応）


---

## 作者情報

- 名前：青木 大和（Yamato Aoki）  
- スタック：PHP/Laravel・MySQL・Docker・GCP・AWS・ETL・データ基盤構築  
- GitHub: [https://github.com/yamato-aoki](https://github.com/yamato-aoki)  
- 今後も、より実務的な改善を継続予定
