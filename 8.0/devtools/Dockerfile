FROM alpine:3.14

MAINTAINER Stephan Wentz <swentz@brainbits.net>

ENV PATH="/composer/vendor/bin:$PATH" \
    COMPOSER_HOME=/composer \
    COMPOSER_ALLOW_SUPERUSER=1 \
    TIMEZONE=Europe/Berlin

WORKDIR /app

COPY ./docker-entrypoint.sh /usr/bin/docker-entrypoint.sh

RUN echo "### install brainbits apk repository" \
    && echo https://packages.brainbits.net/apk/php80 >> /etc/apk/repositories \
    && wget -O /etc/apk/keys/brainbits.rsa.pub https://packages.brainbits.net/apk/brainbits.rsa.pub \

    && echo "### install testing apk repository" \
    && echo https://dl-cdn.alpinelinux.org/alpine/edge/testing >> /etc/apk/repositories \

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
        php8 \

        # composer
        patch \
        php8-iconv \
        php8-mbstring \
        php8-openssl \
        php8-phar \

        # useful
        #php8-bcmath \
        php8-ctype \
        php8-curl \
        php8-dom \
        php8-fileinfo \
        php8-intl \
        php8-opcache \
        php8-pcntl \
        php8-posix \
        php8-session \
        php8-simplexml \
        php8-sockets \
        php8-tokenizer \
        php8-pecl-xdebug \
        php8-xml \
        php8-xmlreader \
        php8-zip \
        php8-zlib \

    && ln -s /usr/bin/php8 /usr/bin/php \

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
    && echo curl -LsS -o /usr/lib/php8/modules/blackfire.so https://packages.blackfire.io/binaries/blackfire-php/1.74.1/blackfire-php-alpine_arm64-php-80.so \
    && curl -LsS -o /usr/lib/php8/modules/blackfire.so https://packages.blackfire.io/binaries/blackfire-php/1.74.1/blackfire-php-alpine_arm64-php-80.so \
    && ls -al /usr/lib/php8/modules \

    # Disable xdebug
    && echo "### disable xdebug" \
    && rm /etc/php8/conf.d/*xdebug.ini \

    # Configure php core
    && echo "### configure php-core" \
    && echo "memory_limit=-1" >> /etc/php8/conf.d/10-core.ini \
    && echo "realpath_cache_size=4096k" >> /etc/php8/conf.d/10-core.ini \
    && echo "realpath_cache_ttl=600" >> /etc/php8/conf.d/10-core.ini \
    && echo "variables_order=EGPCS" >> /etc/php8/conf.d/10-core.ini \

    # Configure php opcache
    && echo "### configure php-opcache" \
    && echo "opcache.enable=1" >> /etc/php8/conf.d/10-opcache.ini \
    && echo "opcache.enable_cli=1" >> /etc/php8/conf.d/10-opcache.ini \
    && echo "opcache.fast_shutdown=1" >> /etc/php8/conf.d/10-opcache.ini \
    && echo "opcache.interned_strings_buffer=8" >> /etc/php8/conf.d/10-opcache.ini \
    && echo "opcache.max_accelerated_files=20000" >> /etc/php8/conf.d/10-opcache.ini \
    && echo "opcache.memory_consumption=256" >> /etc/php8/conf.d/10-opcache.ini \
    && echo "opcache.validate_timestamps=0" >> /etc/php8/conf.d/10-opcache.ini \
    #&& mkdir -p $OPCACHE_DIR \

    # Configure php date
    && echo "### configure php-date" \
    && echo "date.timezone=${TIMEZONE}" >> /etc/php8/conf.d/10-date.ini \

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
    && chmod 777 /etc/php8/conf.d \

    # Cleanup
    && echo "### cleanup" \
    && apk --no-cache del tzdata \
    && rm -rf /var/cache/apk/*

COPY ./startup/*.sh /usr/bin/startup/
COPY ./functions/*.sh /usr/bin/functions/

# Devtools specific

ENV GITHUB_AUTH_TOKEN dc8e9a8cc614fa8e002780fefdadf0798d72a254

COPY config.json /tmp/config.json
COPY installer /tmp/installer

RUN echo "### Install php qa tools" \

    # Install devtools deps
    && apk --no-cache add \
        gnupg \
        ncurses \
        php8 \
        php8-pecl-ast \
        php8-bz2 \
        php8-ctype \
        php8-curl \
        php8-dom \
        php8-fileinfo \
        php8-iconv \
        php8-intl \
        php8-json \
        php8-mbstring \
        php8-openssl \
        php8-pdo \
        php8-phar \
        php8-simplexml \
        php8-sockets \
        php8-tokenizer \
        php8-xml \
        php8-xmlreader \
        php8-xmlwriter \
        php8-zip \

    # Install installer deps
    && composer install -d /tmp/installer \

    # Install tools
    && php /tmp/installer/bin/installer install-packages /tmp/config.json /tmp/build /usr/bin/composer \

    # Create tool tests
    && php /tmp/installer/bin/installer generate-tests /tmp/config.json /tmp/build /tmp/build/usr/share/tools/tests.sh \

    # Copy staged tools to system
    && cp -R /tmp/build/usr/* /usr/ \

    # Cleanup temporary files
    && rm -rf /tmp/build /tmp/config.json /tmp/installer

RUN echo "### [devtools] install packages" \
    && apk --no-cache add \
        php8-pdo_sqlite \
        php8-pecl-pcov \
        php8-phpdbg \

    && ln -s /usr/bin/phpdbg8 /usr/bin/phpdbg \

    # Disable zend.exception_ignore_args (new in php 7.4, defaults to On)
    && sed -i "s#^zend.exception_ignore_args = .*#zend.exception_ignore_args = Off#g" /etc/php8/php.ini \

    # Create php xdebug wrapper
    && echo "### [devtools] install php xdebug-wrapper" \
    && printf "#!/bin/bash\n\nphp -dzend_extension=xdebug.so \$@\n" > /usr/bin/php-xdebug \
    && chmod +x /usr/bin/php-xdebug \

    # Disable pcov
    && printf pcov.enabled=0 >> /etc/php8/conf.d/pcov.ini \

    # Create php pcov wrapper
    && echo "### [devtools] install php pcov-wrapper" \
    && printf "#!/bin/bash\n\nphp -dpcov.enabled=1 \$@\n" > /usr/bin/php-pcov \
    && chmod +x /usr/bin/php-pcov \

    # Show versions
    && echo "### [devtools] configure motd" \
    && echo "" > /etc/motd \
    && echo "`php --version`" >> /etc/motd \
    && echo "" >> /etc/motd \
    && echo "`composer --no-ansi --version`" >> /etc/motd \
    && echo "`composer1 --no-ansi --version`" >> /etc/motd \
    && echo "" >> /etc/motd \
    && echo "Installed tools:" >> /etc/motd \
    && cat /usr/share/tools/motd >> /etc/motd \
    && echo "" >> /etc/motd \

    && echo "cat /etc/motd" > /root/.bash_profile \

    # Cleanup
    && echo "### [devtools] cleanup" \
    && composer clear-cache \
    && rm -rf /var/cache/apk/*

ENTRYPOINT ["/usr/bin/docker-entrypoint.sh"]

CMD ["bash", "-l"]
