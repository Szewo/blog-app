FROM php:8.0-fpm

ENV XDEBUG_MODE=off
ENV XDEBUG_CONFIG=""

RUN pecl install apcu

RUN apt-get update && \
apt-get install -y \
zlib1g-dev autoconf pkg-config libssl-dev libzip-dev

RUN docker-php-ext-install zip bcmath mysqli pdo pdo_mysql

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --filename=composer \
    && php -r "unlink('composer-setup.php');" \
    && mv composer /usr/local/bin/composer

RUN pecl install xdebug; \
    docker-php-ext-enable xdebug;

WORKDIR /usr/src/app

RUN PATH=$PATH:/usr/src/apps/vendor/bin:bin
