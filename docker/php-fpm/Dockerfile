FROM php:7.4-fpm
# RUN apt update \
#     && apt install -y git \
#     && docker-php-ext-install intl opcache pdo pdo_mysql \
    # && pecl install apcu \
    # && docker-php-ext-enable apcu \
    # && docker-php-ext-configure zip \
    # && docker-php-ext-install zip
RUN docker-php-ext-install pdo_mysql

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
# COPY --from=composer /usr/bin/composer /usr/bin/composer
WORKDIR /var/www
CMD composer install --no-dev --optimize-autoloader ; \
    APP_ENV=prod APP_DEBUG=0 php bin/console cache:clear ; \
    php bin/console doctrine:migrations:migrate ; \
    php bin/console doctrine:fixtures:load -n ; \
    php-fpm 
EXPOSE 9000
