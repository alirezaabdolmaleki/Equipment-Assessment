FROM php:8.0-apache

RUN docker-php-ext-install pdo_mysql

COPY deployment/dockerfiles/sarmayex-assessments-web/php.ini /usr/local/etc/php/

COPY app/ /var/www
