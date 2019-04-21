#
# PHP Dependencies
# https://laravel-news.com/multi-stage-docker-builds-for-laravel
#
FROM composer:latest as vendor

COPY database/ database/

COPY composer.json composer.json
COPY composer.lock composer.lock
COPY auth.json auth.json

RUN composer install \
    --no-dev \
    --ignore-platform-reqs \
    --no-interaction \
    --no-plugins \
    --no-scripts \
    --prefer-dist

#
# Frontend
#
FROM node:latest as frontend

RUN mkdir -p /app/public

COPY package.json webpack.mix.js yarn.lock /app/
COPY resources/js/ /app/resources/js/
COPY resources/sass/ /app/resources/sass/

WORKDIR /app

RUN yarn install && yarn production

#
# Application
#
FROM php:apache

# @see https://www.drupal.org/docs/8/system-requirements/drupal-8-php-requirements
# install the PHP extensions we need
# pcntl gd  opcache freeType
RUN set -ex; \
    savedAptMark="$(apt-mark showmanual)"; \
    \
    apt-get update; \
    apt-get install -y --no-install-recommends \
    # https://www.php.net/manual/en/zip.installation.php php7.0-zip
        libjpeg-dev \
        libpng-dev \
        libpq-dev \
            libwebp-dev \
            libjpeg62-turbo-dev \
            libxpm-dev \
            libmcrypt-dev \
            libfreetype6-dev \
            libmcrypt-dev \
        libzip-dev\
    ; \
    \
    docker-php-ext-configure gd \
        --with-png-dir=/usr \
        --with-jpeg-dir=/usr \
        --enable-gd-native-ttf \
        --with-freetype-dir=/usr/include/freetype2 ;\
#docker-php-ext-configure gd --with-png-dir=/usr --with-jpeg-dir=/usr; \
    docker-php-ext-install -j "$(nproc)" \
        gd \
        mbstring \
        pcntl \
        bcmath \
        opcache \
        pdo_mysql \
        zip \
    ; \
    \
# reset apt-mark's "manual" list so that "purge --auto-remove" will remove all build dependencies
    apt-mark auto '.*' > /dev/null; \
    apt-mark manual $savedAptMark; \
    ldd "$(php -r 'echo ini_get("extension_dir");')"/*.so \
        | awk '/=>/ { print $3 }' \
        | sort -u \
        | xargs -r dpkg-query -S \
        | cut -d: -f1 \
        | sort -u \
        | xargs -rt apt-mark manual; \
    \
    apt-get purge -y --auto-remove -o APT::AutoRemove::RecommendsImportant=false; \
    rm -rf /var/lib/apt/lists/*

# set recommended PHP.ini settings
# see https://secure.php.net/manual/en/opcache.installation.php
RUN { \
        echo 'opcache.memory_consumption=128'; \
        echo 'opcache.interned_strings_buffer=8'; \
        echo 'opcache.max_accelerated_files=4000'; \
        echo 'opcache.revalidate_freq=60'; \
        echo 'opcache.fast_shutdown=1'; \
        echo 'opcache.enable_cli=1'; \
    } > /usr/local/etc/php/conf.d/opcache-recommended.ini
###end from drupal & pcntl for horizon


### set php config
COPY docker/uploads.ini /usr/local/etc/php/conf.d/uploads.ini
COPY docker/vhost.conf /etc/apache2/sites-available/000-default.conf
COPY docker/start.sh /usr/local/bin/start

COPY . /var/www/html
COPY --from=vendor /app/vendor/ /var/www/html/vendor/
COPY --from=frontend /app/public/js/ /var/www/html/public/js/
COPY --from=frontend /app/public/css/ /var/www/html/public/css/
COPY --from=frontend /app/mix-manifest.json /var/www/html/mix-manifest.json

RUN chown -R www-data:www-data /var/www/html \
    && chmod -R ug+rwx storage bootstrap/cache \
    && chmod u+x /usr/local/bin/start \
    && a2enmod rewrite
CMD ["/usr/local/bin/start"]
