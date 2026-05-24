FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev libonig-dev libxml2-dev zip unzip git curl

# Install PHP extensions bawaan (MySQL, String, GD, dll)
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# BARU: Install dan aktifkan ekstensi phpredis lewat PECL
RUN pecl install redis && docker-php-ext-enable redis

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copy everything and install dependencies cleanly
COPY . .
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Set up our startup script
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 9000

ENTRYPOINT ["entrypoint.sh"]
