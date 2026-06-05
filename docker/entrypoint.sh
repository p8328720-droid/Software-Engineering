#!/bin/sh

# CHANGED: Paths updated to /var/www/app
chmod -R 775 /var/www/app/storage /var/www/app/bootstrap/cache 2>/dev/null
chown -R www-data:www-data /var/www/app/storage /var/www/app/bootstrap/cache 2>/dev/null

ln -sfn /var/www/app/storage/app/public /var/www/app/public/storage

# Jalur ke file .env di dalam container
ENV_FILE="/var/www/app/.env"

# Cek apakah file .env belum ada, atau ada tapi isinya kosong / tidak punya APP_KEY
if [ ! -f "$ENV_FILE" ] || ! grep -q "APP_KEY=" "$ENV_FILE"; then
    echo "Membuat file .env khusus untuk APP_KEY..."
    
    # Generate key baru secara langsung
    GENERATED_KEY=$(php artisan key:generate --show --no-ansi)
    
    # Tulis atau timpa langsung ke file .env khusus APP_KEY
    echo "APP_KEY=$GENERATED_KEY" > "$ENV_FILE"
    echo "APP_KEY berhasil disimpan secara permanen di .env!"
fi

echo "Menunggu transisi final database..."
until php artisan db:monitor > /dev/null 2>&1; do
    echo "Database sedang inisialisasi/restart, mencoba lagi dalam 3 detik..."
    sleep 3
done

echo "Database siap! Menjalankan migrasi dan seeding..."
php artisan migrate:fresh --force --seed

echo "Menyalakan PHP-FPM..."
exec php-fpm