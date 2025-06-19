# Stage 1: Build frontend assets with Node
FROM node:18-alpine AS node-builder

WORKDIR /app

COPY package*.json ./
RUN npm install

COPY . .

# Ensure env is set
ENV APP_URL=https://gamestormer.onrender.com
RUN npm run build

# Stage 2: Setup PHP Laravel app with PostgreSQL extension
FROM laravelsail/php82-composer

# Install PostgreSQL client and PHP extension
RUN apt-get update && apt-get install -y libpq-dev && \
    docker-php-ext-install pdo_pgsql

WORKDIR /var/www/html

# Copy Laravel app files first
COPY . .

# Copy built frontend assets from node-builder last (overwrite public/build)
COPY --from=node-builder /app/public/build ./public/build

# Install PHP dependencies
RUN composer install --optimize-autoloader --no-dev

# Set permissions for Laravel storage and cache
RUN chown -R www-data:www-data storage bootstrap/cache
RUN chmod -R 755 storage bootstrap/cache

# Create storage symlink
RUN php artisan storage:link || true

# Expose port 8000 (Laravel development server)
EXPOSE 8000

# Start Laravel dev server
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
