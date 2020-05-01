FROM php:7.4-fpm

RUN apt-get update \
    && apt-get -y --no-install-recommends install \
        git \
        libicu-dev \
        libpq-dev \
        libzip-dev \
        locales \
        locales-all \
        unzip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

RUN docker-php-ext-install \
    intl \
    opcache \
    pdo \
    pdo_pgsql \
    zip

COPY --from=composer /usr/bin/composer /usr/bin/
RUN composer global require hirak/prestissimo

ENV COMPOSER_MEMORY_LIMIT=-1

ENV PROMPT_COMMAND="history -a"
ENV HISTTIMEFORMAT="%h %d %H:%M:%S "
ENV HISTFILESIZE=20000
ENV LC_ALL en_US.UTF-8
ENV LANG en_US.UTF-8
ENV LANGUAGE en_US.UTF-8
