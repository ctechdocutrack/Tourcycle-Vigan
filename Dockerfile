FROM php:8.3-apache

WORKDIR /var/www/html

RUN printf "upload_max_filesize = 25M\npost_max_size = 100M\nmax_file_uploads = 20\nmax_execution_time = 120\nmax_input_time = 120\n" > /usr/local/etc/php/conf.d/uploads.ini

RUN docker-php-ext-install mysqli \
    && a2enmod rewrite

COPY . /var/www/html/

RUN mkdir -p \
    /var/www/html/admin/uploads/destinations \
    /var/www/html/admin/uploads/audio \
    && chown -R www-data:www-data /var/www/html/admin/uploads \
    && chmod -R 775 /var/www/html/admin/uploads \
    && chown -R www-data:www-data /var/www/html/admin/controller

EXPOSE 80