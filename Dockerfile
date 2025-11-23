# ============================
# 1) COMPOSER STAGE
# ============================
FROM composer:2 AS composer_stage

WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --no-progress \
    --no-scripts   # important: avoid artisan commands during build

COPY . .

# ============================
# 2) FRONTEND STAGE (Vite)
# ============================
FROM node:20-alpine AS frontend_stage

WORKDIR /app

# Copy package files
COPY package*.json ./

# Install dependencies only if package.json exists
RUN if [ -f package.json ]; then npm install; fi

# Copy complete app
COPY . .

# Build frontend (if package.json exists)
RUN if [ -f package.json ]; then npm run build; fi


# ============================
# 3) FINAL RUNTIME IMAGE
# ============================
FROM php:8.2-apache

# Install system packages and PHP extensions
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libicu-dev \
    libpq-dev \
    && docker-php-ext-install pdo_mysql pdo_pgsql zip intl \
    && rm -rf /var/lib/apt/lists/*

# Enable Apache rewrite
RUN a2enmod rewrite

# Set Apache public directory
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/000-default.conf \
    /etc/apache2/apache2.conf

WORKDIR /var/www/html

# Copy app + vendor from composer stage
COPY --from=composer_stage /app ./

# Copy Vite build output
COPY --from=frontend_stage /app/public/build ./public/build

# Permissions
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R ug+rwx storage bootstrap/cache

# ===============================
# RUN MIGRATIONS (TEMPORARY)
# ===============================
RUN php artisan migrate --force || true

EXPOSE 80

CMD ["apache2-foreground"]
