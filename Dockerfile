# Stage 1: Setup Laravel + Build assets
FROM laravelsail/php82-composer AS app-builder

# Install Node.js (you can also switch to a full image that has both PHP & Node)
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - && \
    apt-get install -y nodejs

# Install PostgreSQL client and PHP extension
RUN apt-get update && apt-get install -y libpq-dev && \
    docker-php-ext-install pdo_pgsql

WORKDIR /var/www/html

# Copy entire Laravel project
COPY . .

# Set environment variable
ENV APP_URL=https://gamestormer.onrender.com

# Install dependencies
RUN composer install --optimize-autoloader --no-dev
RUN npm install

# Clear config/cache so Vite picks up fresh APP_URL
RUN php artisan config:clear && \
    php artisan route:clear && \
    php artisan view:clear && \
    php artisan optimize:clear

# Build frontend
RUN npm run build

# Stage 2: Lightweight runtime image
FROM laravelsail/php82-composer

# Install PostgreSQL client and PHP extension
RUN apt-get update && apt-get install -y libpq-dev && \
    docker-php-ext-install pdo_pgsql

WORKDIR /var/www/html

# Copy Laravel app from builder stage
COPY --from=app-builder /var/www/html /var/www/html

# Set permissions
RUN chown -R www-data:www-data storage bootstrap/cache
RUN chmod -R 755 storage bootstrap/cache

# Link storage
RUN php artisan storage:link || true

EXPOSE 8000

CMD php artisan serve --host=0.0.0.0 --port=8000
