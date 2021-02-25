FROM php:7.0-apache

RUN docker-php-ext-install mysqli pdo pdo_mysql 
RUN apt-get update && apt-get install -y zlib1g-dev libicu-dev g++ 
RUN docker-php-ext-configure intl
RUN docker-php-ext-install intl

RUN a2enmod rewrite