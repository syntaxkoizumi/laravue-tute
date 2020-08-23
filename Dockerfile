FROM node:14-alpine AS node
FROM php:7.4-fpm-alpine

COPY install-composer.sh /

# RUN apt-get update \
#     && apt-get install -y wget git unzip libpq-dev \
#     && : 'Install Node.js' \
#     &&  curl -sL https://deb.nodesource.com/setup_12.x | bash - \
#     && apt-get install -y nodejs \
#     && : 'Install PHP Extensions' \
#     && docker-php-ext-install -j$(nproc) pdo_pgsql \
#     && : 'Install Composer' \
#     && chmod 755 /install-composer.sh \
#     && /install-composer.sh \
#     && mv composer.phar /usr/local/bin/composer

RUN apk add --update \
    bash \
    wget \
    git \
    unzip \
    build-base \
    curl-dev \
    linux-headers \
    libxml2-dev \
    libxslt-dev \
    postgresql-dev

# install PHP extensions
RUN docker-php-ext-install -j$(nproc) pdo \
    pdo_pgsql \
    pgsql

RUN : 'Install Composer' \
    && chmod 755 /install-composer.sh \
    && /install-composer.sh \
    && mv composer.phar /usr/local/bin/composer

# add node.js npm
COPY --from=node /usr/local /usr/local
# RUN apk add --update --no-cache python make g++
# RUN rm /usr/local/bin/yarn /usr/local/bin/yarnpkg

WORKDIR /var/www/html/laravue-tute
