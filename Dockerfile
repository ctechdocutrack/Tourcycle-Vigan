FROM php:8.3-apache

WORKDIR /var/www/html

RUN docker-php-ext-install mysqli \
    && a2enmod rewrite

COPY . /var/www/html/

RUN mkdir -p \
    /var/www/html/admin/uploads/destinations \
    /var/www/html/admin/uploads/audio \
    && chown -R www-data:www-data /var/www/html/admin/uploads \
    && chmod -R 775 /var/www/html/admin/uploads

EXPOSE 80