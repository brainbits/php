FROM alpine:3.14

MAINTAINER Stephan Wentz <swentz@brainbits.net>

ENV PATH="/composer/vendor/bin:$PATH" \
    COMPOSER_HOME=/composer \
    COMPOSER_ALLOW_SUPERUSER=1 \
    TIMEZONE=Europe/Berlin

WORKDIR /app

COPY ./docker-entrypoint.sh /usr/bin/docker-entrypoint.sh

RUN echo "### install brainbits apk repository" \
    && echo https://packages.brainbits.net/apk/php74 >> /etc/apk/repositories \
    && wget -O /etc/apk/keys/brainbits.rsa.pub https://packages.brainbits.net/apk/brainbits.rsa.pub \

    # Upgrade base packages
    && echo "### upgrade packages" \
    && apk --no-cache upgrade \

    # Install packages
    && echo "### install packages" \
    && apk --no-cache add \
        bash \
        grep \
        ca-certificates \
        git \
        curl \
        unzip \
        openssh \
        tzdata \
        jq \
        yq \
        php7 \

        # composer
        patch \
        php7-iconv \
        php7-json \
        php7-mbstring \
        php7-openssl \
        php7-phar \

        # useful
        php7-bcmath \
        php7-ctype \
        php7-curl \
        php7-dom \
        php7-fileinfo \
        php7-intl \
        php7-opcache \
        php7-pcntl \
        php7-posix \
        php7-session \
        php7-simplexml \
        php7-sockets \
        php7-tokenizer \
        php7-xdebug \
        php7-xml \
        php7-xmlreader \
        php7-zip \
        php7-zlib \

    # Download and install composer
    && echo "### install composer" \
    && curl -LsS -o /tmp/composer-setup.php https://getcomposer.org/installer \
    && curl -LsS -o /tmp/composer-setup.sig https://composer.github.io/installer.sig \
    && php -r "if (hash('SHA384', file_get_contents('/tmp/composer-setup.php')) !== trim(file_get_contents('/tmp/composer-setup.sig'))) { echo 'Invalid installer' . PHP_EOL; exit(1); }" \
    && php /tmp/composer-setup.php --1 --no-ansi --install-dir=/usr/bin --filename=composer1 \
    && rm /tmp/composer-setup.php \
    && rm /tmp/composer-setup.sig \
    && composer1 --ansi --version --no-interaction \
    # Allow write in cache for everybody
    && chmod 777 /composer/cache \

    # Install composer 2.x
    && cp /usr/bin/composer1 /usr/bin/composer2 \
    && composer2 selfupdate --2 \
    && composer2 --ansi --version --no-interaction \

    # Link composer2
    && ln -s /usr/bin/composer2 /usr/bin/composer \

    # Install Blackfire
    && echo "### install blackfire-probe" \
    && echo curl -LsS -o /usr/lib/php7/modules/blackfire.so https://packages.blackfire.io/binaries/blackfire-php/1.74.1/blackfire-php-alpine_arm64-php-74.so \
    && curl -LsS -o /usr/lib/php7/modules/blackfire.so https://packages.blackfire.io/binaries/blackfire-php/1.74.1/blackfire-php-alpine_arm64-php-74.so \
    && ls -al /usr/lib/php7/modules \

    # Disable xdebug
    && echo "### disable xdebug" \
    && rm /etc/php7/conf.d/*xdebug.ini \

    # Configure php core
    && echo "### configure php-core" \
    && echo "memory_limit=-1" >> /etc/php7/conf.d/10-core.ini \
    && echo "realpath_cache_size=4096k" >> /etc/php7/conf.d/10-core.ini \
    && echo "realpath_cache_ttl=600" >> /etc/php7/conf.d/10-core.ini \
    && echo "variables_order=EGPCS" >> /etc/php7/conf.d/10-core.ini \

    # Configure php opcache
    && echo "### configure php-opcache" \
    && echo "opcache.enable=1" >> /etc/php7/conf.d/10-opcache.ini \
    && echo "opcache.enable_cli=1" >> /etc/php7/conf.d/10-opcache.ini \
    && echo "opcache.fast_shutdown=1" >> /etc/php7/conf.d/10-opcache.ini \
    && echo "opcache.interned_strings_buffer=8" >> /etc/php7/conf.d/10-opcache.ini \
    && echo "opcache.max_accelerated_files=20000" >> /etc/php7/conf.d/10-opcache.ini \
    && echo "opcache.memory_consumption=256" >> /etc/php7/conf.d/10-opcache.ini \
    && echo "opcache.validate_timestamps=0" >> /etc/php7/conf.d/10-opcache.ini \
    #&& mkdir -p $OPCACHE_DIR \

    # Configure php date
    && echo "### configure php-date" \
    && echo "date.timezone=${TIMEZONE}" >> /etc/php7/conf.d/10-date.ini \

    # Use bash instead of ash
    && echo "### configure shell" \
    && sed -i "s#^/bin/ash#/bin/bash#g" /etc/passwd \

    # Permissions for custom scripts
    && echo "### fix permissions" \
    && chmod a+x /usr/bin/docker-entrypoint.sh \

    # Configure timezone
    && echo "### configure timezone" \
    && cp /usr/share/zoneinfo/${TIMEZONE} /etc/localtime \
    && echo "${TIMEZONE}" > /etc/timezone \

    # allow user
    && chmod 777 /etc/php7/conf.d \

    # Cleanup
    && echo "### cleanup" \
    && apk --no-cache del tzdata \
    && rm -rf /var/cache/apk/*

COPY ./startup/*.sh /usr/bin/startup/
COPY ./functions/*.sh /usr/bin/functions/

# CLI specific

ENTRYPOINT ["/usr/bin/docker-entrypoint.sh"]

CMD ["php", "-a"]
