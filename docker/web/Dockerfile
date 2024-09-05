FROM php:8.1.11-apache
RUN docker-php-ext-install -j$(nproc) mysqli pdo_mysql

# Composer をインストール
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Composer を使って依存関係をインストール
RUN composer install