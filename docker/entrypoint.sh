#!/bin/sh

# Pastikan permission storage & cache aman ter-update
chmod -R 775 /var/www/app/storage /var/www/app/bootstrap/cache 2>/dev/null
chown -R www-data:www-data /var/www/app/storage /var/www/app/bootstrap/cache 2>/dev/null

# Symlink storage Laravel
ln -sfn /var/www/app/storage/app/public /var/www/app/public/storage

ENV_FILE="/var/www/app/.env"

# Jika .env belum ada, copy dari example
if [ ! -f "$ENV_FILE" ]; then
    cp /var/www/app/.env.example "$ENV_FILE"
fi

# Generate key aman bawaan laravel
if ! grep -q "APP_KEY=" "$ENV_FILE" || [ -z "$(grep "APP_KEY=" "$ENV_FILE" | cut -d= -f2)" ]; then
    php artisan key:generate --force --no-ansi
fi

echo "Menunggu koneksi database siap..."
until php -r "try { new PDO('mysql:host='.getenv('DB_HOST').';dbname='.getenv('DB_DATABASE'), getenv('DB_USERNAME'), getenv('DB_PASSWORD')); exit(0); } catch(Exception \$e) { exit(1); }" > /dev/null 2>&1; do
    sleep 3
done

echo "Database siap! Menjalankan migrasi..."
php artisan migrate:fresh --force --seed

# 1. Jalankan PHP-FPM di background (menggunakan flag -D / Daemonize)
echo "Menyalakan PHP-FPM..."
php-fpm -D

# 2. Jalankan Nginx di foreground sebagai proses utama container (PID 1)
echo "Menyalakan Nginx..."
exec nginx -g "daemon off;"