#!/bin/sh

chmod -R 775 /var/www/storage /var/www/bootstrap/cache 2>/dev/null
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache 2>/dev/null

ln -sfn /var/www/storage/app/public /var/www/public/storage

# Jalur ke file .env di dalam container
ENV_FILE="/var/www/.env"

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
# Menggunakan exec agar PHP-FPM menjadi proses utama (PID 1) di container ini
exec php-fpm