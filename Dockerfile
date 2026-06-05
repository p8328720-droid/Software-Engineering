FROM php:8.4-fpm

# Install system dependencies + Nginx polosan
RUN apt-get update && apt-get install -y \
    libpng-dev libonig-dev libxml2-dev zip unzip git curl nginx

# Install PHP extensions bawaan
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install dan aktifkan ekstensi phpredis lewat PECL
RUN pecl install redis && docker-php-ext-enable redis

# Set direktif upload langsung di PHP configuration layer
RUN echo "upload_max_filesize=128M\npost_max_size=128M" > /usr/local/etc/php/conf.d/uploads.ini

# Amankan log Nginx agar langsung keluar di docker logs
RUN ln -sf /dev/stdout /var/log/nginx/access.log && ln -sf /dev/stderr /var/log/nginx/error.log

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/app

# Copy seluruh project ke dalam image
COPY . .

# Pasang config Nginx
COPY docker/nginx.conf /etc/nginx/sites-available/default
RUN ln -sf /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default

# Jalankan optimasi composer
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Atur kepemilikan folder
RUN chown -R www-data:www-data /var/www/app

# Copy and setup entrypoint script
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 80

ENTRYPOINT ["entrypoint.sh"]