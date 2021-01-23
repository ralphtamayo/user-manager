FROM php:7.4-apache

RUN a2enmod rewrite

# PHP Extensions
RUN apt-get update
RUN apt-get install -y libfreetype6-dev libjpeg-dev libpng-dev \
  && docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/
RUN apt-get install -y libicu-dev && docker-php-ext-configure intl
RUN apt-get install -y zlib1g-dev libzip-dev librabbitmq-dev libssh-dev
RUN docker-php-ext-install bcmath exif gd intl opcache pdo pdo_mysql zip
RUN pecl install apcu && docker-php-ext-enable apcu
RUN pecl install xdebug && docker-php-ext-enable xdebug
RUN pecl install amqp && docker-php-ext-enable amqp

# Git and Composer and setfacl
RUN apt-get install -y git unzip acl
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

ARG uid

RUN adduser --disabled-password --gecos '' --uid $uid docker-user && adduser docker-user www-data
WORKDIR /var/www/user-manager
