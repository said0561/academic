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
    --no-scripts   # muhimu: usikimbize artisan scripts wakati wa build

COPY . .

# ============================
# 2) FRONTEND STAGE (Vite)
# ============================
FROM node:20-alpine AS frontend_stage

WORKDIR /app

# Copy Node package files (package.json, package-lock.json, etc.)
COPY package*.json ./

# Install node dependencies only if package.json exists
RUN if [ -f package.json ]; then npm install; fi

# Copy the rest of the app
COPY . .

# Build frontend (Vite) only if package.json exists
RUN if [ -f package.json ]; then npm run build; fi


# ============================
# 3) FINAL RUNTIME IMAGE
# ============================
FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libicu-dev \
    && docker-php-ext-install pdo pdo_mysql zip intl \
    && rm -rf /var/lib/apt/lists/*

RUN a2enmod rewrite

# Set Laravel public folder as Apache root
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/000-default.conf /etc/apache2/apache2.conf

WORKDIR /var/www/html

# Copy app with vendor from composer stage
COPY --from=composer_stage /app ./

# Copy Vite build output only
COPY --from=frontend_stage /app/public/build ./public/build

# Fix permissions
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R ug+rwx storage bootstrap/cache

EXPOSE 80

CMD ["apache2-foreground"]
