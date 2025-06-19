# Stage 1: Build frontend assets with Node
FROM node:18-alpine AS node-builder

WORKDIR /app

COPY package*.json ./
RUN npm install

COPY . .
RUN npm run build

# Stage 2: Setup PHP Laravel app with PostgreSQL extension
FROM laravelsail/php82-composer

# Install PostgreSQL client and PHP extension
RUN apt-get update && apt-get install -y libpq-dev && \
    docker-php-ext-install pdo_pgsql

WORKDIR /var/www/html

# Copy built frontend assets from node-builder
COPY --from=node-builder /app/public/build ./public/build

# Copy Laravel app files
COPY . .

# Install PHP dependencies
RUN composer install --optimize-autoloader --no-dev

RUN chown -R www-data:www-data storage bootstrap/cache
RUN chmod -R 755 storage bootstrap/cache

RUN php artisan storage:link || true

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
