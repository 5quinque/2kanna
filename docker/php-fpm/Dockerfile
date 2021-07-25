FROM php:7.4-fpm
RUN apt-get update && \
    apt-get install -y libmagickwand-dev libzip-dev && \
    pecl install imagick && \
    pecl install zip && \
    docker-php-ext-enable imagick zip && \
    rm -rf /var/lib/apt/lists/*
RUN docker-php-ext-install intl opcache pdo_mysql

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
WORKDIR /var/www
CMD composer install ; \
    APP_ENV=dev APP_DEBUG=0 php bin/console cache:clear ; \
    php bin/console doctrine:migrations:migrate ; \
    php bin/console doctrine:fixtures:load -n ; \
    php-fpm 
EXPOSE 9000

# CMD composer install --no-dev --optimize-autoloader ; \
#     APP_ENV=prod APP_DEBUG=0 php bin/console cache:clear ; \
