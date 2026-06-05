#!/bin/sh

# Set up permissions
chmod -R 775 /var/www/app/storage /var/www/app/bootstrap/cache 2>/dev/null
chown -R www-data:www-data /var/www/app/storage /var/www/app/bootstrap/cache 2>/dev/null

ln -sfn /var/www/app/storage/app/public /var/www/app/public/storage

# Jalur ke file .env
ENV_FILE="/var/www/app/.env"
if [ ! -f "$ENV_FILE" ] || ! grep -q "APP_KEY=" "$ENV_FILE"; then
    echo "Membuat file .env khusus untuk APP_KEY..."
    GENERATED_KEY=$(php artisan key:generate --show --no-ansi)
    echo "APP_KEY=$GENERATED_KEY" > "$ENV_FILE"
fi

echo "Menunggu transisi final database..."
until php artisan db:monitor > /dev/null 2>&1; do
    echo "Database sedang inisialisasi/restart, mencoba lagi dalam 3 detik..."
    sleep 3
done

echo "Database siap! Menjalankan migrasi..."
php artisan migrate:fresh --force --seed

# 1. Jalankan Nginx di background
echo "Menyalakan Nginx..."
nginx -g "daemon on;"

# 2. Jalankan PHP-FPM di foreground (sebagai proses utama container)
echo "Menyalakan PHP-FPM..."
exec php-fpm