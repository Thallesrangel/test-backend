FROM php:7.3

# Configurations
COPY .docker/php.ini /usr/local/etc/php/php.ini

# Update
RUN apt-get -y update --fix-missing && \
    apt-get upgrade -y && \
    apt-get --no-install-recommends install -y apt-utils

# Install useful tools and install important libaries
RUN apt install -y libxslt-dev
RUN apt-get -y --no-install-recommends install nano wget dialog libsqlite3-dev libsqlite3-0 && \
    apt-get -y --no-install-recommends install zlib1g-dev libzip-dev libicu-dev && \
    apt-get -y --no-install-recommends install --fix-missing apt-utils build-essential curl && \
    apt-get -y --no-install-recommends install --fix-missing libcurl4-gnutls-dev zip openssl && \
    apt-get -y --no-install-recommends install --fix-missing default-mysql-client

# Instalando supervisor
RUN apt-get -y install systemd

# Git
RUN apt-get -y --no-install-recommends install --fix-missing git

# Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Other PHP7 Extensions
RUN docker-php-ext-install pdo_mysql && \
    docker-php-ext-install mysqli && \
    docker-php-ext-install curl && \
    docker-php-ext-install curl && \
    docker-php-ext-install tokenizer && \
    docker-php-ext-install zip && \
    docker-php-ext-install -j$( nproc) intl && \
    docker-php-ext-install mbstring && \
    docker-php-ext-install gettext

# Cleanup
RUN rm -rf /usr/src/* && \
    rm -rf /var/lib/apt/lists/*
