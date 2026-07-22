# Readoo!

読書を「読んで終わり」にしない。  
本から得た知識をストックし、具体的な行動・習慣へつなげるための読書管理アプリです。

![Laravel](https://img.shields.io/badge/Laravel-13-FF2D20?style=flat&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.5-777BB4?style=flat&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-9-4479A1?style=flat&logo=mysql&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-3.x-06B6D4?style=flat&logo=tailwindcss&logoColor=white)
![Docker](https://img.shields.io/badge/Docker-Compose-2496ED?style=flat&logo=docker&logoColor=white)

---

## Readoo!について

Readoo!では、読書を次の3つの要素で管理します。

| 要素            | 役割                 |
| ------------- | ------------------ |
| **Book**      | 読書情報を管理する中心となるデータ  |
| **Knowledge** | 本から得た知識や学び         |
| **Action**    | 知識を実生活で実践するための行動計画 |

Actionは必ずBookに紐づき、必要に応じてKnowledgeとも関連付けられます。

---

## 開発の動機

読書をしても、時間が経つと内容を忘れてしまったり、知識を記録するだけで終わったりすることがあります。

そこで、次の課題を解決するためにReadoo!を開発しました。

- 読書から得た知見やアイデアを、実生活に活かしたい
- 本から得た知識を、参照元とともに整理して蓄積したい
- 「読んだ」「記録した」で終わらせず、具体的な行動へ落とし込む仕組みを作りたい

本・知識・行動を一つの流れとして管理することで、読書を実践につなげることを目指しています。

---

## 主な機能

| モデル | 概要 |
|---|---|
| **Book** | 本の登録・管理。読書ステータス（未読／読書中／読了）、著者・出版社・ジャンルなどの情報を整理 |
| **Knowledge** | 本から得た学びを記録。どの本のどの箇所から得た知識かを、参照元情報とともに管理 |
| **Action** | Knowledgeを5W1H（いつ・どこで・誰に・何を・なぜ・どうやって）で具体的な行動計画へ変換。必ずBookに紐づき、必要に応じてKnowledgeとも関連付け可能 |

### 機能一覧

- Book / Knowledge / Actionの一覧・詳細・登録・編集・削除
- Book詳細画面からのKnowledge・Action登録
- Knowledge詳細画面からのAction登録
- 一覧画面のソート・ページネーション
- Laravel Breezeを利用したユーザー認証
- パスワードリセットメールの送信
- Policyによる他ユーザーのデータへのアクセス制御
- Soft DeleteによるBook / Knowledge / Actionの論理削除
- Knowledgeを削除しても、そこから生まれたActionをBookに紐づけたまま保持
- ユーザー退会時の関連データ削除
- 同一メールアドレスでの再登録

---

## 技術スタック

| レイヤー | 技術 |
|---|---|
| Backend | Laravel 13 / PHP 8.5 |
| Frontend | Blade / Tailwind CSS / Alpine.js |
| Database | MySQL 9 |
| Authentication | Laravel Breeze |
| Development | Docker Compose |
| Containers | nginx / PHP / MySQL / Mailpit |
| Testing | PHPUnit / Feature Test |
| Infrastructure（予定） | AWS EC2 / RDS / ALB / ACM |

---

## 画面イメージ

> スクリーンショットはデプロイ完了後に追加予定です。

<!--
例：

### Book一覧

![Book一覧](docs/images/books-index.png)

### Knowledge詳細

![Knowledge詳細](docs/images/knowledge-show.png)

### Action登録

![Action登録](docs/images/action-create.png)
-->

---

## 設計上のこだわり

### 1. Book・Knowledge・Actionを区別できるUI

本・知識・行動それぞれにテーマカラーを設定しています。

- Book：緑
- Knowledge：青
- Action：オレンジ

一覧・詳細・登録・編集画面でテーマカラーを統一し、現在どの種類の情報を操作しているかを直感的に判断できるようにしました。

### 2. Knowledge削除後もActionを保持

ActionはBookへの紐づけを必須とし、Knowledgeへの紐づけは任意にしています。

Knowledgeを削除しても、そこから生まれたActionは削除せず、Bookに紐づいた独立した行動として残します。

参照先を失ったActionは、編集画面から同じBookに属する別のKnowledgeへ付け替えることもできます。

### 3. 他ユーザーのデータへのアクセス制御

Book / Knowledge / Actionの閲覧・更新・削除にはPolicyを利用しています。

ログインユーザーとデータの所有者が一致する場合のみ操作を許可し、URLやリクエスト値を変更しても、他ユーザーのデータを操作できないようにしています。

また、KnowledgeやActionを登録する際は、FormRequestで次の内容を検証しています。

- 指定されたBookがログインユーザー自身のBookであること
- Actionに指定されたKnowledgeが、選択したBookに属していること
- Action更新時に、別のBookに属するKnowledgeへ付け替えられないこと

### 4. 関連IDを入力値から直接保存しない設計

`user_id`や`book_id`などの所有関係に関わるIDは、フォームから送られた値をそのまま一括保存しません。

ログインユーザーやBookのリレーションを経由して保存することで、意図しない所有者の変更を防いでいます。

### 5. 論理削除と退会処理

Book / Knowledge / ActionにはSoft Deleteを採用しています。

一方、ユーザーが退会する場合は、トランザクション内で次の処理を行います。

1. ユーザーに紐づくBookを物理削除
2. DBのカスケード制約によりKnowledge・Actionを物理削除
3. メールアドレスを退避して、元のアドレスを再利用可能にする
4. ユーザー本体を論理削除

これにより、退会後に同じメールアドレスで再登録できるようにしています。

### 6. 一覧取得時のパフォーマンス対策

Bookに紐づくKnowledge・Actionや、一覧に表示する参照元BookはEager Loadingで取得し、N+1問題を防いでいます。

一覧画面の並び替え条件はホワイトリストで管理し、想定していないカラムがクエリに指定されないようにしています。

---

## テスト

主要機能についてFeature Testを実装しています。

### 主なテスト内容

- Book / Knowledge / ActionのCRUD
- 未ログインユーザーの保護ページへのアクセス制御
- 自分のデータだけが一覧に表示されること
- 必須項目のバリデーション
- Book / Knowledge / Actionが論理削除されること
- Knowledgeの参照元Bookを更新時に変更できないこと
- Actionの参照元Bookを更新時に変更できないこと
- Actionへ同じBookに属するKnowledgeを関連付けられること
- ActionからKnowledgeとの関連を解除できること
- Knowledgeを削除しても、関連するActionが削除されないこと
- 登録画面への導線に応じて、登録後に適切な画面へ遷移すること

### テスト実行

```bash
docker compose exec app php artisan test
```

---

## 開発プロセス

本プロジェクトでは、Laravelの実装技術だけでなく、システム開発全体を一気通貫で経験することを目的としました。

次の工程を個人で進めています。

1. 課題設定・企画
2. ユーザー課題と提供価値の整理
3. 要件定義
4. MVP範囲の決定
5. 画面設計
6. データベース設計
7. Laravelによる実装
8. Feature Test
9. 結合テスト
10. UI / UX改善
11. README作成
12. AWSへのデプロイ

### AIの活用

実装にはClaude Codeを開発支援ツールとして活用しました。

AIが生成したコードをそのまま採用するのではなく、次の役割は自身で担っています。

- 解決する課題とアプリのコンセプト決定
- 要件定義とMVP範囲の判断
- 画面・DB・権限・データ削除方針の設計
- 実装内容の確認
- ローカル環境での動作確認
- Feature Testと結合テスト
- 不具合の原因調査
- UI / UXの改善
- GitHub Issue・ブランチ・Pull Requestによる進捗管理

AIをコード生成だけでなく、設計・実装・レビュー時の対話相手として利用しながら、プロジェクトの完遂を目指しています。

---

## 環境構築

### 前提条件

以下が利用できる環境を想定しています。

- Git
- Docker
- Docker Compose

### セットアップ

```bash
git clone https://github.com/JuriMino/readoo.git
cd readoo

cp src/.env.example src/.env

docker compose up -d --build

docker compose exec app composer install
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate --seed

docker compose exec app npm install
docker compose exec app npm run build
```

セットアップ完了後、以下へアクセスしてください。

- アプリケーション：[http://localhost:8080](http://localhost:8080)
- Mailpit：[http://localhost:8025](http://localhost:8025)

---

## 環境変数

`src/.env.example`をコピーして`src/.env`を作成した後、Docker Composeの設定に合わせて環境変数を変更してください。

```env
APP_NAME=Readoo
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8080

DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=readoo
DB_USERNAME=readoo
DB_PASSWORD=secret

MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
```

- `db`は`compose.yaml`で定義しているMySQLコンテナのサービス名です
- DB名・ユーザー名・パスワードは`compose.yaml`の設定に合わせてください
- `mailpit`は開発用のメール確認コンテナです
- パスワードリセットメールなどはMailpitの管理画面で確認できます

---

## コードフォーマット

Laravel Pintを使用してコードを整形できます。

```bash
docker compose exec app ./vendor/bin/pint
```

---

## 今後の予定

### v1公開まで

- READMEの完成
- AWS環境の構築
- EC2・RDSへのデプロイ
- HTTPS対応
- 本番環境での動作確認
- スクリーンショットの追加
- 公開URLの記載

### 今後検討している機能

- キーワード検索
- タグによる絞り込み
- タグのマスタ管理
- Actionの実行状況管理
- リマインド機能
- 読書・行動状況の可視化
- 外部連携を想定したAPI設計
- APIを利用した別アプリケーションの開発

---

## リポジトリ

[https://github.com/JuriMino/readoo](https://github.com/JuriMino/readoo)
