#!/bin/sh

# Pastikan permission storage & cache aman ter-update
chmod -R 775 /var/www/app/storage /var/www/app/bootstrap/cache 2>/dev/null
chown -R www-data:www-data /var/www/app/storage /var/www/app/bootstrap/cache 2>/dev/null

# Symlink storage Laravel
ln -sfn /var/www/app/storage/app/public /var/www/app/public/storage

ENV_FILE="/var/www/app/.env"

# FIX: Jika .env belum ada sama sekali, copy dari .env.example agar isinya tidak kosongan
if [ ! -f "$ENV_FILE" ]; then
    echo "File .env tidak ditemukan, mengopi dari .env.example..."
    cp /var/www/app/.env.example "$ENV_FILE"
fi

# FIX: Generate key menggunakan command bawaan Laravel agar tidak menimpa variable lain
if ! grep -q "APP_KEY=" "$ENV_FILE" || [ -z "$(grep "APP_KEY=" "$ENV_FILE" | cut -d= -f2)" ]; then
    echo "Mendeteksi APP_KEY kosong, meng-generate key baru..."
    php artisan key:generate --force --no-ansi
fi

echo "Menunggu koneksi database siap..."
# FIX: Menggunakan PHP native PDO check yang jauh lebih akurat dibanding db:monitor bawaan
until php -r "try { new PDO('mysql:host='.getenv('DB_HOST').';dbname='.getenv('DB_DATABASE'), getenv('DB_USERNAME'), getenv('DB_PASSWORD')); exit(0); } catch(Exception \$e) { exit(1); }" > /dev/null 2>&1; do
    echo "Database belum siap atau sedang restart, mencoba lagi dalam 3 detik..."
    sleep 3
done

echo "Database siap! Menjalankan migrasi dan seeding..."
php artisan migrate:fresh --force --seed

# Jalankan Nginx sebagai daemon di background
echo "Menyalakan Nginx..."
nginx -g "daemon on;"

# Jalankan PHP-FPM di foreground sebagai PID 1 container utama
echo "Menyalakan PHP-FPM..."
exec php-fpm