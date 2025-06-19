# Stage 1: Setup Laravel + Build assets
FROM laravelsail/php82-composer AS app-builder

# Install Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - && \
    apt-get install -y nodejs

# Install PostgreSQL client and PHP extension
RUN apt-get update && apt-get install -y libpq-dev && \
    docker-php-ext-install pdo_pgsql

WORKDIR /var/www/html


# Copy Laravel files
COPY . .

# Set APP_URL so Vite uses it
ENV APP_URL=https://gamestormer.onrender.com

# Install PHP and JS dependencies
RUN composer install --optimize-autoloader --no-dev
RUN npm install

# Only now is Laravel ready enough to run Artisan commands
RUN php artisan config:clear && \
    php artisan route:clear && \
    php artisan view:clear && \
    php artisan optimize:clear

# Now build Vite assets
RUN npm run build
