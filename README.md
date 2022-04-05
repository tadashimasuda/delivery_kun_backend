#README
## アプリ概要
- アプリ名
-  作った理由：フードデリバリー配達員の配達記録の手間を省くため
## 注力した点
- REST APIの作成
- Google認証を用いた機能
- AWSを利用したインフラ環境の構築
- 業務中に邪魔にならないUI
- チャートを用いた表示

## 機能
- ログイン機能
- 新規登録機能
- ユーザー情報編集機能
- 配達記録
- 記録の確認
- 記録の編集

## 使用した言語、技術、サービス
- 言語
    - アプリ開発 : Flutter
    - サーバーサイド : Laravel

- DB
    - Mysql

- 技術
    - 認証周り
        Laravel passport(token認証),socialite(Google OAuth) 

- サービス
    - クラウドサービス
        AWS