FROM php:8.1-fpm-alpine

RUN apk add --update --no-cache \
    postgresql-dev \
    gmp \
    gmp-dev \
    libpng-dev \
    graphviz \
    && docker-php-ext-configure gmp \
    && docker-php-ext-install \
       pdo_pgsql \
       sockets \
       gmp \
       gd

#RUN apk add --no-cache rabbitmq-c-dev && \
#    mkdir -p /usr/src/php/ext/amqp && \
#    curl -fsSL https://pecl.php.net/get/amqp | tar xvz -C "/usr/src/php/ext/amqp" --strip 1 && \
#    docker-php-ext-install amqp

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY . /srv/app
WORKDIR /srv/app
