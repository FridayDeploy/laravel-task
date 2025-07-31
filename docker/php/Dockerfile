FROM php:8.4-fpm

ARG userId=1000

RUN docker-php-ext-install pdo_mysql pcntl

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php composer-setup.php
RUN php -r "unlink('composer-setup.php');"
RUN mv composer.phar /usr/local/bin/composer

RUN apt-get update && apt-get install -y git unzip

RUN usermod -u $userId www-data
USER www-data
RUN export HOME=/var/www/html