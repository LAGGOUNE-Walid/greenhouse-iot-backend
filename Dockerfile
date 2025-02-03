# Use the official PHP image with FPM
FROM php:8.2-fpm
# Install common php extension dependencies
RUN apt-get update && apt-get install -y \
    libfreetype-dev \
    libsqlite3-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    zip unzip git curl \
    zlib1g-dev \
    libzip-dev \
    unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_sqlite \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install zip

# Set the working directory
COPY ./src /var/www/app
WORKDIR /var/www/app

RUN chown -R www-data:www-data /var/www/app \
    && chmod -R 775 /var/www/app/storage


# install composer
COPY --from=composer:2.6.5 /usr/bin/composer /usr/local/bin/composer

# copy composer.json to workdir & install dependencies
COPY ./src/composer.json ./
RUN composer install

# Set the default command to run php-fpm
CMD ["php-fpm"]