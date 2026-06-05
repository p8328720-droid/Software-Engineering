FROM php:8.4-fpm

# Install system dependencies + Nginx
RUN apt-get update && apt-get install -y \
    libpng-dev libonig-dev libxml2-dev zip unzip git curl nginx

# Install PHP extensions bawaan
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install dan aktifkan ekstensi phpredis lewat PECL
RUN pecl install redis && docker-php-ext-enable redis

RUN echo "upload_max_filesize=128M\npost_max_size=128M" > /usr/local/etc/php/conf.d/uploads.ini

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/app

# Copy seluruh project ke container
COPY . .

# Copy config Nginx langsung ke dalem image
COPY docker/nginx.conf /etc/nginx/sites-available/default
RUN ln -sf /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default

RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Atur ulang ownership ke www-data untuk Laravel, tapi entrypoint butuh root buat nyalain Nginx
RUN chown -R www-data:www-data /var/www/app

COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 80

ENTRYPOINT ["entrypoint.sh"]