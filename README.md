# attend
#[Dockerビルド]

git clone git@github.com:yuuzaki2000/freema.git
docker-compose up -d --build
#[Laravel環境構築]

docker-compose exec php bash
composer install
新たに、srcディレクトリ下に、.envファイルを作成し、.env.exampleファイルの内容をコピーする
.envファイルに以下の環境変数を追加 DB_CONNECTION=mysql DB_HOST=mysql 　DB_PORT=3306 DB_DATABASE=laravel_db DB_USERNAME=laravel_user DB_PASSWORD=laravel_pass
アプリケーションキーを作成する php aritsan key:generate
マイグレーションを実行する php artisan migrate:
シーディングを実行する php artisan db:seed

#[実行環境] MySQL 8.0.26 PHP 7.4.9-fpm Laravel 8 nginx 1.21.1
