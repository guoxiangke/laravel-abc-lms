#
# PHP Dependencies
# https://laravel-news.com/multi-stage-docker-builds-for-laravel
#
FROM composer:latest as vendor

COPY database/ database/

COPY composer.json composer.json
COPY composer.lock composer.lock
# COPY auth.json auth.json

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

COPY package.json webpack.mix.js package-lock.json /app/
COPY resources/js/ /app/resources/js/
COPY resources/sass/ /app/resources/sass/

WORKDIR /app

RUN npm install && npm run production

#
# Application
#
FROM drupal:8-fpm
# https://hub.docker.com/_/drupal

# install the PHP extensions  pcntl & cron
RUN set -ex; \
  apt-get update; \
  apt-get install -y --no-install-recommends \
    vim \
    libwebp-dev \
    libjpeg62-turbo-dev \
    libxpm-dev \
    libmcrypt-dev \
    libfreetype6-dev \
    libmcrypt-dev \
  ; \
  \
  docker-php-ext-configure gd \
    --with-png-dir=/usr \
    --with-jpeg-dir=/usr \
    --with-freetype-dir=/usr/include/freetype2 \
  ; \
  docker-php-ext-install -j "$(nproc)" \
    gd \
    mbstring \
    pcntl \
    bcmath \
  ; \
  \
  rm -rf /var/lib/apt/lists/* \
  && rm -rf /var/www/html \
  && mkdir /var/www/html

COPY . /var/www/html
COPY --from=vendor /app/vendor/ /var/www/html/vendor/
COPY --from=frontend /app/public/js/ /var/www/html/public/js/
COPY --from=frontend /app/public/css/ /var/www/html/public/css/
COPY --from=frontend /app/mix-manifest.json /var/www/html/mix-manifest.json

COPY docker/start.sh /usr/local/bin/start

RUN mkdir -p /var/www/html/storage/app/public/loginqr \
  && mkdir -p /var/www/html/storage/app/public/referrals

RUN chown -R www-data:www-data storage bootstrap/cache \
  && chmod -R ug+rwx storage bootstrap/cache \
  && chmod u+x /usr/local/bin/start

CMD ["/usr/local/bin/start"]
