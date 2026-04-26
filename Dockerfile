FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    libsqlite3-dev \
    zip unzip git \
    && docker-php-ext-install pdo pdo_mysql pdo_sqlite
    


RUN a2enmod rewrite

COPY . /var/www/html/

WORKDIR /var/www/html/
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN composer install

RUN chown -R www-data:www-data /var/www/html/storage