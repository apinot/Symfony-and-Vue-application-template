FROM php:8.3-fpm

# Install PHP extensions
RUN apt update && apt upgrade -y && apt install -y \
    libzip-dev \
    libpq-dev \
    libicu-dev \
    zip \
    git

# Install extension PHP
RUN docker-php-ext-configure \
    intl \
&& docker-php-ext-install \
    pdo \
    pdo_pgsql \
    zip \
    intl \
    bcmath

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

# Get latest Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy ini file
COPY ./php.ini $PHP_INI_DIR/php.ini

# Create system user to run Composer and Artisan Commands
RUN useradd -G www-data,root -u 1000 -d /home/apinot apinot
RUN mkdir -p /home/apinot/.composer && \
    chown -R apinot:apinot /home/apinot

# Set working directory
WORKDIR /var/www/html

USER apinot