FROM php:8.2-apache

# Install dependencies dan ekstensi PHP
RUN apt-get update && apt-get install -y \
    libzip-dev unzip curl git \
    && docker-php-ext-install pdo pdo_mysql zip bcmath

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Set Apache DocumentRoot ke /var/www/html/public
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

# Ubah default konfigurasi Apache agar DocumentRoot-nya mengarah ke /public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/000-default.conf

# Copy Composer dari image resmi
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy semua file project Laravel ke container
COPY . /var/www/html

# Jalankan composer install
RUN composer install --no-dev --optimize-autoloader

# Set permission folder Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Expose port 80
EXPOSE 80
