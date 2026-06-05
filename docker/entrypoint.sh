#!/bin/sh

# Amankan permission internal container
chmod -R 775 /var/www/app/storage /var/www/app/bootstrap/cache 2>/dev/null
chown -R www-data:www-data /var/www/app/storage /var/www/app/bootstrap/cache 2>/dev/null
ln -sfn /var/www/app/storage/app/public /var/www/app/public/storage

ENV_FILE="/var/www/app/.env"
if [ ! -f "$ENV_FILE" ]; then cp /var/www/app/.env.example "$ENV_FILE"; fi
if ! grep -q "APP_KEY=" "$ENV_FILE" || [ -z "$(grep "APP_KEY=" "$ENV_FILE" | cut -d= -f2)" ]; then
    php artisan key:generate --force --no-ansi
fi

echo "Menunggu koneksi database..."
until php -r "try { new PDO('mysql:host='.getenv('DB_HOST').';dbname='.getenv('DB_DATABASE'), getenv('DB_USERNAME'), getenv('DB_PASSWORD')); exit(0); } catch(Exception \$e) { exit(1); }" > /dev/null 2>&1; do
    sleep 3
done

echo "Database siap! Menjalankan migrasi..."
php artisan migrate:fresh --force --seed
php artisan config:clear
php artisan cache:clear

# Jalankan PHP-FPM sebagai proses utama tunggal (PID 1)
echo "PHP-FPM siap menerima request dari Nginx!"
exec php-fpm