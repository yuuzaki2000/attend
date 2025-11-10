#Dockerビルド

git clone git@github.com:yuuzaki2000/attend.git

docker-compose up -d --build

#Laravel環境構築

 docker-compose exec php bash
 
 composer install

新たに、srcディレクトリ下に、.envファイルを作成し、.env.exampleファイルの内容をコピーする

.envファイルに以下の環境変数を追加 

DB_CONNECTION=mysql 

DB_HOST=mysql 　

DB_PORT=3306 

DB_DATABASE=laravel_db 

DB_USERNAME=laravel_user 

DB_PASSWORD=laravel_pass

アプリケーションキーを作成する php aritsan key:generate

マイグレーションを実行する php artisan migrate

シーディングを実行する php artisan db:seed

#Permission

プロジェクトのディレクトリにて、下記のようなコマンドを実行する

sudo chmod -R 777 src/*

#実行環境 MySQL 8.0.26 PHP 8.1-fpm Laravel 8 nginx 1.21.1

#メール認証について

mailtrapというツールを使用しています。

以下のリンクから会員登録をしてください。　

https://mailtrap.io/

メールボックスのIntegrationsから 「laravel 7.x and 8.x」を選択し、　

.envファイルのMAIL_MAILERからMAIL_ENCRYPTIONまでの項目をコピー＆ペーストしてください。　

MAIL_FROM_ADDRESSは任意のメールアドレスを入力してください。  ex) test@example.com

MY_INBOX_URL = (MailtrapのMy InboxのURL)　　　

ex)  https://mailtrap.io/inboxes/********/messages

#管理者ログイン（初期）

メールアドレス：admin@admin.admin
パスワード：adminadmin
