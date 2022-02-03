FROM composer/composer as composer
COPY composer.json /app/
COPY composer.lock /app/

RUN composer install --no-dev --no-scripts


FROM php:8.1-apache
ENV APACHE_DOCUMENT_ROOT /var/www/public
RUN a2enmod rewrite

COPY --from=composer /app/vendor /var/www/vendor
COPY bin /var/www/bin
COPY config /var/www/config
COPY public /var/www/public
COPY src /var/www/src
COPY templates /var/www/templates
COPY var /var/www/var
COPY .env /var/www/

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
