FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nginx

# Clear cache
RUN apt-get clean && rm -rf /var/lib/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy existing application directory contents
COPY . /var/www

# KUNCI UTAMA: Berikan hak akses folder agar Laravel bisa menulis log/cache
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Install dependencies
RUN composer install --no-interaction --optimize-autoloader --no-dev

# Setup Nginx configuration (Langsung timpa ke sites-enabled agar pasti terbaca)
COPY .docker/nginx.conf /etc/nginx/sites-enabled/default
COPY .docker/nginx.conf /etc/nginx/sites-available/default

# Paksa Nginx mengirim log error-nya ke konsol Railway
RUN ln -sf /dev/stdout /var/log/nginx/access.log && ln -sf /dev/stderr /var/log/nginx/error.log

EXPOSE 80

# Jalankan Nginx di background, dan PHP-FPM di foreground sebagai pengunci container
CMD nginx && php-fpm
