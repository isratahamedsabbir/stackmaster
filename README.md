## Install
npm i
npm run build
composer update
php artisan migrate
php artisan migrate:fresh --seed

## Run
npm run dev
php artisan serve --host=0.0.0.0 --port=8050
php artisan reverb:start --debug
php artisan queue:work