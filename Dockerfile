FROM php:8.5-cli

# Install system dependencies for SQLite
RUN apt-get update && apt-get install -y \
    libsqlite3-dev sqlite3 \
    && docker-php-ext-install pdo_sqlite \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY composer.json composer.lock* ./
RUN composer install --no-dev --optimize-autoloader

COPY . .

# Initialize database with schema and seed data
RUN mkdir -p database \
    && sqlite3 database/flight_booking_system.sqlite < db/schema.sql \
    && sqlite3 database/flight_booking_system.sqlite < db/seed.sql

EXPOSE 80

CMD php -S 0.0.0.0:${PORT:-80} -t public public/router.php
