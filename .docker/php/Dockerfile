FROM php:8.0-fpm-alpine

MAINTAINER javier.tapia.d@gmail.com

RUN apk update; \
    apk upgrade;

RUN apk add --no-cache zip libzip-dev

RUN docker-php-ext-install -j$(nproc) iconv mysqli pdo_mysql zip

RUN apk add --no-cache $PHPIZE_DEPS \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

# install Composer
RUN curl -sS https://getcomposer.org/installer | php
RUN mv composer.phar /usr/local/bin/composer
ENV PATH /root/.composer/vendor/bin:$PATH
