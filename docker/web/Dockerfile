FROM php:8.1.11-apache
RUN docker-php-ext-install -j$(nproc) mysqli pdo_mysql

RUN apt-get update && apt-get install -y \
    unzip \
    git \
    libzip-dev \
    zip \
    && docker-php-ext-install zip

# Composer をインストール
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
