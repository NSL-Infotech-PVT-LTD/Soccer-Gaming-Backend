cd /var/www/dev/tournie 
git pull origin development-0.0.6
composer install
sudo chmod -R 0777 /var/www/dev/tournie/storage /var/www/dev/tournie/bootstrap /var/www/dev/tournie/public/uploads 
/usr/bin/php /var/www/dev/tournie/artisan migrate
exit