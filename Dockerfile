# PHP 8.4 with FPM (used with Nginx)
FROM php:8.4-fpm

# System dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libicu-dev \
    g++ \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        pdo_mysql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        intl \
        zip \
        opcache

# Redis extension (optional but common for cache/session)
RUN pecl install redis \
    && docker-php-ext-enable redis

# Xdebug (optional for local development)
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

# Copy Composer
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy source code
COPY . .

# Symfony specific permissions
RUN mkdir -p var/cache var/log \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 var
