FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \git \ 
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# 拡張インストール
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# composer コピー
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
