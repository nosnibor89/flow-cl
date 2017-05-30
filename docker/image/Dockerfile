FROM phusion/baseimage

# Environment Variables
ENV PHP_USER www-data

# phusion setup
ENV HOME /home/www

# phusion initialiser
CMD ["/sbin/my_init"]

# Install PHP 7 (ensure noninterative setup)
RUN add-apt-repository -y ppa:ondrej/php
RUN apt-get update
RUN apt-get install -y php7.1

# PHP7 Libs
RUN  apt-get install -y php7.1-curl \
                       php7.1-dev \
                       php7.1-fpm \
                       php7.1-xdebug

# Git
RUN apt-get install -y git

# NodeJS 6.*
RUN curl -sL https://deb.nodesource.com/setup_6.x | bash -
RUN apt-get install -y nodejs

# Give www-data access to the home folder
RUN mkdir -p /home/www \
    && chown www-data:staff /home/www

# PHP Composer
RUN curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer

## add startup scripts for php7.1-fpm
ADD phpfpm.sh /etc/service/phpfpm/run
RUN chmod +x /etc/service/phpfpm/run

# cleanup apt and lists
RUN apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

EXPOSE 9000
EXPOSE 80
