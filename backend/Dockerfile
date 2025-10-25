FROM php:8.3-apache

RUN apt-get update && apt-get install -y \
    zip \
    unzip \
    git \
    libzip-dev \
    && docker-php-ext-install pdo pdo_mysql zip

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN a2enmod rewrite

ENV APACHE_DOCUMENT_ROOT /var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/html

COPY . .

RUN mkdir -p /var/www/html/storage/logs \
    && mkdir -p /var/www/html/coverage-report \
    && chmod -R 777 /var/www/html/storage/logs \
    && chmod -R 777 /var/www/html/coverage-report

RUN composer install --no-interaction --prefer-dist

RUN chown -R www-data:www-data /var/www/html

EXPOSE 80