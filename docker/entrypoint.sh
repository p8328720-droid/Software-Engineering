#!/bin/sh

chmod -R 775 /var/www/storage /var/www/bootstrap/cache 2>/dev/null
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache 2>/dev/null

ln -sfn /var/www/storage/app/public /var/www/public/storage

if [ ! -f .env ]; then

    cp .env.example .env
fi

if ! grep -q "APP_KEY=base64" .env; then
    php artisan key:generate
fi

echo "Menunggu transisi final database..."
sleep 5
until php artisan db:monitor > /dev/null 2>&1; do
    echo "Database sedang inisialisasi/restart, mencoba lagi dalam 3 detik..."
    sleep 3
done

echo "Database siap! Menjalankan migrasi dan seeding..."
php artisan migrate:fresh --force --seed

echo "Menyalakan PHP-FPM..."
# Menggunakan exec agar PHP-FPM menjadi proses utama (PID 1) di container ini
exec php-fpm