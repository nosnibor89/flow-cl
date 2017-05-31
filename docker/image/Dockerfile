FROM php:7-fpm

# Environment Variables
ENV PHP_USER www-data

# Install PHP 7 (ensure noninterative setup)
RUN apt-get update
RUN apt install -y curl \
    && apt install -y libcurl3 \
    && apt install -y libcurl3-dev \
    && apt install -y git \
    && apt install -y zip

# PHP7 Libs
RUN pecl install xdebug
RUN docker-php-ext-enable xdebug
RUN docker-php-ext-install curl
RUN docker-php-ext-install json

# Give www-data access to the home folder
RUN mkdir -p /home/www \
    && chown www-data:staff /home/www

# PHP Composer
RUN curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer

# cleanup apt and lists
RUN apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

EXPOSE 9000
EXPOSE 80
