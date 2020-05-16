cd /var/www/dev/tournie 
git pull origin development-0.0.1
composer install
sudo chmod -R 0777 storage bootstrap public/uploads 
php artisan migrate
exit