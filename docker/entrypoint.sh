#!/bin/sh

# 1. Pastikan file .env tersedia
if [ ! -f .env ]; then
    cp .env.example .env
fi

# 2. Amankan APP_KEY jika belum tergenerasi
if ! grep -q "APP_KEY=base64" .env; then
    php artisan key:generate
fi

echo "1. Memeriksa & menginstal Composer dependencies..."
composer install --no-interaction --prefer-dist --optimize-autoloader

echo "2. Menunggu transisi final database..."
sleep 5
until php artisan db:monitor > /dev/null 2>&1; do
    echo "Database sedang inisialisasi/restart, mencoba lagi dalam 3 detik..."
    sleep 3
done

echo "3. Database siap! Menjalankan migrasi dan seeding..."
php artisan migrate:fresh --force --seed

echo "4. Menyalakan PHP-FPM..."
# Menggunakan exec agar PHP-FPM menjadi proses utama (PID 1) di container ini
exec php-fpm