#!/bin/sh

# Amankan permission internal container
chmod -R 775 /var/www/app/storage /var/www/app/bootstrap/cache 2>/dev/null
chown -R www-data:www-data /var/www/app/storage /var/www/app/bootstrap/cache 2>/dev/null
ln -sfn /var/www/app/storage/app/public /var/www/app/public/storage

KEY_FILE="/var/www/app/storage/app_key.txt"

if [ -z "$APP_KEY" ]; then
    if [ ! -f "$KEY_FILE" ]; then
        echo "APP_KEY kosong & file belum ada. Generate key permanen pertama kali..."
        php artisan key:generate --show --no-ansi > "$KEY_FILE"
    fi
    echo "Memuat APP_KEY yang tersimpan dari volume..."
    export APP_KEY=$(cat "$KEY_FILE")
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