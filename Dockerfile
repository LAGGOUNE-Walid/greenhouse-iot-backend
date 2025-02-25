# Use the official PHP image with FPM
FROM php:8.2-fpm

ARG user
ARG uid

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

# install composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# RUN useradd -G www-data,root -u $uid -d /home/$user $user
# RUN mkdir -p /home/$user/.composer && \
#     chown -R $user:$user /home/$user
USER www-data
WORKDIR /var/www/html/app
RUN chown -R www-data:www-data /var/www/html/app
# USER $user


CMD ["php-fpm"]