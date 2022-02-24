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

# FPM specific

RUN echo "### [fpm] install packages" \
    && apk --no-cache add \
        dumb-init \
        php8-fpm \
        php8-session \

    # Create php-fpm symlink
    && ln -s /usr/sbin/php-fpm8 /usr/sbin/php-fpm \

    # FPM config
    && echo "### [fpm] configure fpm" \
    && chown -R nobody:nobody /app \
    && sed -i "s#^;log_limit = .*#log_limit = 8192#g" /etc/php8/php-fpm.conf \
    && sed -i "s#^;error_log =.*#error_log = /proc/self/fd/2#g" /etc/php8/php-fpm.conf \
    && sed -i "s#^listen =.*#listen = 9000#g" /etc/php8/php-fpm.d/www.conf \
    && sed -i "s#^;clear_env =.*#clear_env = no#g" /etc/php8/php-fpm.d/www.conf \
    && sed -i "s#^;catch_workers_output =.*#catch_workers_output = yes#g" /etc/php8/php-fpm.d/www.conf \
    && sed -i "s#^;decorate_workers_output =.*#decorate_workers_output = no#g" /etc/php8/php-fpm.d/www.conf \
    && sed -i "s#^;access\.log =.*#access.log = /proc/self/fd/1#g" /etc/php8/php-fpm.d/www.conf \
    && sed -i "s#^;php_admin_value\[error_log\].*#php_admin_value[error_log] = /proc/self/fd/2#g" /etc/php8/php-fpm.d/www.conf \
    && sed -i "s#^;php_admin_flag\[log_errors\].*#php_admin_flag[log_errors] = on#g" /etc/php8/php-fpm.d/www.conf \
    && printf "\nphp_admin_value[memory_limit] = 128M\n" >> /etc/php8/php-fpm.d/www.conf \

    # PHP config
    && echo "### [fpm] configure php-fpm" \
    && echo "post_max_size=200M" >> /etc/php8/conf.d/20-core.ini \
    && echo "upload_max_filesize=200M" >> /etc/php8/conf.d/20-core.ini \

    # Cleanup
    && echo "### [fpm] cleanup" \
    && rm -rf /var/cache/apk/*

EXPOSE 9000

ENTRYPOINT ["/usr/bin/docker-entrypoint.sh"]

CMD ["/usr/sbin/php-fpm", "-F"]
