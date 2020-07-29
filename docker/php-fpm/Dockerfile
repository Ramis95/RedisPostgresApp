FROM php:7.3-fpm

#COPY . /var/www/html

RUN apt-get update \
&& docker-php-ext-install pdo pdo_mysql \
&& chown -R www-data:www-data /var/www \
&& chmod -R 777 /var/www/html \
&& apt-get install -y libpq-dev \
&& docker-php-ext-install pdo pdo_pgsql \
&& docker-php-ext-install pgsql

