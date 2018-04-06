FROM php:7.2

RUN apt-get update && \
    apt-get install -qy graphviz

ADD https://www.phpdoc.org/phpDocumentor.phar /usr/local/bin/phpdoc
RUN chmod ugo+rx /usr/local/bin/phpdoc

VOLUME /app
WORKDIR /app
ENTRYPOINT ["/usr/local/bin/phpdoc"]
