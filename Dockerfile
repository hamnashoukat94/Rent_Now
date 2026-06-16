# Use official PHP image
FROM php:8.2-fpm

# Install system dependencies for extensions
RUN apt-get update && apt-get install -y \
    git curl unzip zip \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libzip-dev \
    zlib1g-dev \
    pkg-config \
    libonig-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) pdo_mysql mbstring bcmath gd zip

# Install Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

COPY . .

# Install dependencies
ENV COMPOSER_MEMORY_LIMIT=-1
RUN composer install --no-dev --optimize-autoloader

# Expose Render port
EXPOSE 10000

# Start Laravel using PHP’s built-in server
CMD php -S 0.0.0.0:$PORT -t public
