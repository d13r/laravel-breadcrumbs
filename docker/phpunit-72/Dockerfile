FROM php:7.2

RUN pecl install xdebug && \
    docker-php-ext-enable xdebug

VOLUME /app
WORKDIR /app
CMD vendor/bin/phpunit
