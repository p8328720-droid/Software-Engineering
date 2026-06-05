FROM php:8.4-fpm

# Install system dependencies + Nginx paket OS
RUN apt-get update && apt-get install -y \
    libpng-dev libonig-dev libxml2-dev zip unzip git curl nginx

# Install PHP extensions bawaan
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install dan aktifkan ekstensi phpredis lewat PECL
RUN pecl install redis && docker-php-ext-enable redis

# Set direktif upload langsung di PHP configuration layer
RUN echo "upload_max_filesize=128M\npost_max_size=128M" > /usr/local/etc/php/conf.d/uploads.ini

# FIX: Alirkan log Nginx ke stdout & stderr agar bisa dibaca via `docker logs`
RUN ln -sf /dev/stdout /var/log/nginx/access.log && ln -sf /dev/stderr /var/log/nginx/error.log

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/app

# Copy seluruh source code project ke dalam image
COPY . .

# Copy config Nginx langsung ke dalam image dan set sebagai default site
COPY docker/nginx.conf /etc/nginx/sites-available/default
RUN ln -sf /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default

# Jalankan optimasi composer (Gunakan --allow-root karena dijalankan saat build stage)
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Atur kepemilikan folder ke www-data agar Laravel bisa nulis log/cache
RUN chown -R www-data:www-data /var/www/app

# Copy and setup entrypoint script
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Expose port 80 untuk Nginx dan port 9000 untuk PHP-FPM internal
EXPOSE 80 9000

ENTRYPOINT ["entrypoint.sh"]