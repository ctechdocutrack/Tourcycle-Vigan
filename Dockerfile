
FROM php:8.3-apache

WORKDIR /var/www/html

RUN docker-php-ext-install mysqli \
    && a2enmod rewrite

COPY . /var/www/html/

EXPOSE 80
