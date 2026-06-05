FROM php:8.4-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev libonig-dev libxml2-dev zip unzip git curl

# Install PHP extensions bawaan
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install dan aktifkan ekstensi phpredis lewat PECL
RUN pecl install redis && docker-php-ext-enable redis

# FIX: Set maximum upload sizes directly in PHP configuration to prevent 500 errors
RUN echo "upload_max_filesize=128M\npost_max_size=128M" > /usr/local/etc/php/conf.d/uploads.ini

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

USER www-data

# CHANGED: Updated default directory to /var/www/app
WORKDIR /var/www/app

COPY --chown=www-data:www-data . .

RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Set up our startup script
USER root
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh
USER www-data

EXPOSE 9000

ENTRYPOINT ["entrypoint.sh"]