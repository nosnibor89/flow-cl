FROM php:7.1-fpm-alpine

# Environment Variables
ENV PHP_USER www-data

# Install PHP 7 (ensure noninterative setup)
RUN apk add --update autoconf \
    curl \
    pcre-dev \
    gcc \
    libcurl \
    curl-dev \
    git \
    zip \
    # PHP7 Libs
    && docker-php-ext-install curl json 
# EXPOSE 9000
# EXPOSE 80
