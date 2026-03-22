FROM php:8.5-apache

# Use prefork MPM (required by mod_php) and enable mod_rewrite
RUN rm -f /etc/apache2/mods-enabled/mpm_event.* /etc/apache2/mods-enabled/mpm_worker.* \
    && a2enmod mpm_prefork rewrite

# Install system dependencies for SQLite
RUN apt-get update && apt-get install -y \
    libsqlite3-dev sqlite3 \
    && docker-php-ext-install pdo_sqlite \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set document root to public/
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!AllowOverride None!AllowOverride All!g' /etc/apache2/apache2.conf

WORKDIR /var/www/html

COPY composer.json composer.lock* ./
RUN composer install --no-dev --optimize-autoloader

COPY . .

# Initialize database with schema and seed data
RUN mkdir -p database \
    && sqlite3 database/flight_booking_system.sqlite < db/schema.sql \
    && sqlite3 database/flight_booking_system.sqlite < db/seed.sql \
    && chown -R www-data:www-data database

EXPOSE 80

# Support Railway's PORT env var (defaults to 80 for local dev)
CMD sed -i "s/Listen 80/Listen ${PORT:-80}/" /etc/apache2/ports.conf && \
    sed -i "s/:80/:${PORT:-80}/" /etc/apache2/sites-available/000-default.conf && \
    apache2-foreground
