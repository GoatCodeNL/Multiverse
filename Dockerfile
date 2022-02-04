FROM composer/composer as composer
COPY composer.json /app/
COPY composer.lock /app/
COPY src /app/src

RUN composer install \
    --ignore-platform-reqs \
    --no-interaction \
    --no-scripts \
    --prefer-dist \
    --no-dev &&\
    composer dump-autoload

FROM node:12 as node
WORKDIR /app
COPY package.json ./
COPY assets ./assets
COPY yarn.lock ./
COPY webpack.config.js ./

RUN ls -lah
RUN yarn install
RUN yarn build

FROM php:8.1-apache
ENV APACHE_DOCUMENT_ROOT /var/www/public
WORKDIR /var/www
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN a2enmod rewrite

COPY --from=composer /app/vendor /var/www/vendor
COPY --from=node /app/public/build /var/www/public/build
COPY bin /var/www/bin
COPY config /var/www/config
COPY public /var/www/public
COPY src /var/www/src
COPY templates /var/www/templates
COPY var /var/www/var
COPY .env.prod /var/www/.env
COPY composer.json /var/www/

