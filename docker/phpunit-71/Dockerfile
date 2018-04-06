FROM php:7.1

RUN pecl install xdebug && \
    docker-php-ext-enable xdebug

VOLUME /app
WORKDIR /app
CMD vendor/bin/phpunit
