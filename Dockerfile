FROM php:8.2-apache

# Install dependencies dan ekstensi PHP yang dibutuhkan Laravel
RUN apt-get update && apt-get install -y \
    libzip-dev unzip curl git \
    && docker-php-ext-install pdo pdo_mysql zip bcmath

# Aktifkan mod_rewrite Apache (penting untuk Laravel routing)
RUN a2enmod rewrite

# Set DocumentRoot Apache ke /var/www/html/public
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

# Update konfigurasi default VirtualHost untuk gunakan DocumentRoot baru
RUN sed -i "s|DocumentRoot /var/www/html|DocumentRoot ${APACHE_DOCUMENT_ROOT}|g" /etc/apache2/sites-available/000-default.conf

# Copy Composer dari official composer image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory di dalam container
WORKDIR /var/www/html

# Copy seluruh source code Laravel ke dalam container
COPY . /var/www/html

# Jalankan composer install
RUN composer install --no-dev --optimize-autoloader

# Set permission storage dan cache Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache 
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Expose port 80 (Apache)
EXPOSE 80
