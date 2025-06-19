# Base image with PHP, Composer, and Laravel-ready extensions
FROM laravelsail/php82-composer

# Install PostgreSQL client libraries and PHP PostgreSQL extension
RUN apt-get update && apt-get install -y libpq-dev && \
    docker-php-ext-install pdo_pgsql

# Set working directory
WORKDIR /var/www/html

# Copy application code
COPY . .

# Install PHP dependencies
RUN composer install --optimize-autoloader --no-dev

# Set permissions
RUN chown -R www-data:www-data storage bootstrap/cache

# Laravel storage symlink
RUN php artisan storage:link || true

# Expose the port Laravel serves on
EXPOSE 8000

# Start the Laravel server
CMD php artisan serve --host=0.0.0.0 --port=8000
