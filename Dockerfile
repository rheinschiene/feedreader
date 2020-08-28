FROM php:7.4-apache

RUN apt-get update && apt-get upgrade

COPY app /var/www/html/
WORKDIR "/var/www/html/"

COPY 000-default.conf /etc/apache2/sites-available

RUN chown -R www-data:www-data .
RUN find . -type f -exec chmod 0400 {} \;
RUN find . -type d -exec chmod 0500 {} \;
RUN chmod 700 /var/www/html/runtime
RUN chmod 700 /var/www/html/web/assets

RUN docker-php-ext-install pdo pdo_mysql mysqli
RUN a2enmod rewrite

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
