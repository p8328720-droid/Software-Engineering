FROM php:8.4-fpm

# Install system dependencies murni buat Laravel
RUN apt-get update && apt-get install -y \
    libpng-dev libonig-dev libxml2-dev zip unzip git curl

# Install PHP extensions bawaan
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install dan aktifkan ekstensi phpredis lewat PECL
RUN pecl install redis && docker-php-ext-enable redis

# Set direktif upload langsung di PHP configuration layer
RUN echo "upload_max_filesize=128M\npost_max_size=128M" > /usr/local/etc/php/conf.d/uploads.ini

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/app

# COPY seluruh kode ke dalam image PHP
COPY . .

RUN composer install --no-interaction --prefer-dist --optimize-autoloader
RUN chown -R www-data:www-data /var/www/app

COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 9000

ENTRYPOINT ["entrypoint.sh"]