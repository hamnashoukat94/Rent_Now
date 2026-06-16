# Use official PHP image with GD and ZIP already available
FROM php:8.2-cli

# Install only essential system packages
RUN apt-get update && apt-get install -y \
    git curl unzip zip \
    && docker-php-ext-install pdo_mysql mbstring bcmath

# Install Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

COPY . .

# Install dependencies
ENV COMPOSER_MEMORY_LIMIT=-1
RUN composer install --optimize-autoloader --no-dev

# Expose Render port
EXPOSE 10000

# Start Laravel server
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=$PORT"]
