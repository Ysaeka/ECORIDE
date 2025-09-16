FROM php:8.2.12-apache

COPY ./php/ECORIDE/ /var/www/html/

RUN apt-get update -y && apt-get upgrade -y && apt-get install git libssl-dev -y

RUN pecl install mongodb && docker-php-ext-enable mongodb

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

RUN apt-get update \
    && docker-php-ext-install mysqli pdo pdo_mysql \
    && docker-php-ext-enable pdo_mysql

RUN apt-get update && apt-get install -y \
                libfreetype-dev \
                libjpeg62-turbo-dev \
                libpng-dev \
        && docker-php-ext-configure gd --with-freetype --with-jpeg \
        && docker-php-ext-install -j $(nproc) gd\
        && docker-php-ext-enable gd

RUN echo "extension=mongodb.so" >> /usr/local/etc/php/php.ini

RUN chmod 777 -R /var/www/html/uploads/

ENTRYPOINT ["apache2-foreground"]