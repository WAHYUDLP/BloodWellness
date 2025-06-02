FROM php:8.2-apache

# Install dependencies dan ekstensi PHP
RUN apt-get update && apt-get install -y \
    libzip-dev unzip curl git \
    && docker-php-ext-install pdo pdo_mysql zip bcmath

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Copy Composer dari image resmi
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory di container
WORKDIR /var/www/html

# Copy seluruh source code ke container
COPY . /var/www/html

# Jalankan composer install (buat install vendor)
RUN composer install --no-dev --optimize-autoloader

# Set permission folder Laravel
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache

# Expose port 80
EXPOSE 80
