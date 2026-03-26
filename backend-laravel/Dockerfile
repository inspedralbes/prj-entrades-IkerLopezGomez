FROM php:8.3-fpm-alpine

# Instal·lació de dependències del sistema
RUN apk add --no-cache \
    git \
    curl \
    libpng-dev \
    oniguruma-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev

# Instal·lació d'extensions de PHP
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Obtenir el darrer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configurar el directori de treball
WORKDIR /var/www

CMD ["php-fpm"]
