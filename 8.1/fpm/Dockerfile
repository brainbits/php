FROM php:8.1-fpm-alpine3.15

MAINTAINER Stephan Wentz <swentz@brainbits.net>

ENV PATH="/composer/vendor/bin:$PATH" \
    COMPOSER_HOME=/composer \
    COMPOSER_ALLOW_SUPERUSER=1 \
    PHP_INI_SCAN_DIR="${PHP_INI_DIR}/conf.d" \
    PHP_EXTENSION_DIR="/usr/local/lib/php/extensions/no-debug-non-zts-20210902" \
    TIMEZONE=Europe/Berlin

WORKDIR /app

COPY ./docker-entrypoint.sh /usr/bin/docker-entrypoint.sh

RUN echo "### Install alpine packages" \

    # Add required packages
    && apk --no-cache add \
        bash \
        grep \
        ca-certificates \
        git \
        curl \
        unzip \
        openssh \
        tzdata \
        patch \
        icu-libs \
        libzip \
        jq \
        yq \

    # Use bash instead of ash
    && echo "### configure shell" \
    && sed -i "s#^/bin/ash#/bin/bash#g" /etc/passwd \

    # Configure timezone
    && echo "### configure timezone" \
    && cp /usr/share/zoneinfo/${TIMEZONE} /etc/localtime \
    && echo "${TIMEZONE}" > /etc/timezone

RUN echo "### Use production php configuration" \
    && ln -s /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini \
    && echo "### Install composer" \
    && curl -LsS -o /tmp/composer-setup.php https://getcomposer.org/installer \
    && curl -LsS -o /tmp/composer-setup.sig https://composer.github.io/installer.sig \
    && php -r "if (hash('SHA384', file_get_contents('/tmp/composer-setup.php')) !== trim(file_get_contents('/tmp/composer-setup.sig'))) { echo 'Invalid installer' . PHP_EOL; exit(1); }" \
    && php /tmp/composer-setup.php --2 --no-ansi --install-dir=/usr/bin --filename=composer \
    && rm /tmp/composer-setup.php \
    && rm /tmp/composer-setup.sig \
    && composer --ansi --version --no-interaction \

    # Allow write in cache for everybody
    && mkdir /composer/cache \
    && chmod 777 /composer/cache \

    # Install Blackfire
    && echo "### install blackfire-probe" \
    && echo curl -LsS -o ${PHP_EXTENSION_DIR}/blackfire.so https://packages.blackfire.io/binaries/blackfire-php/1.74.1/blackfire-php-alpine_arm64-php-81.so \
    && curl -LsS -o ${PHP_EXTENSION_DIR}/blackfire.so https://packages.blackfire.io/binaries/blackfire-php/1.74.1/blackfire-php-alpine_arm64-php-81.so \
    && ls -al /usr/local/lib/php/extensions/no-debug-non-zts-20210902 \

    # Disable xdebug
    #&& echo "### disable xdebug" \
    #&& rm /usr/local/etc/conf.d/*xdebug.ini \

    # Configure php core
    && echo "### configure php-core" \
    && echo "memory_limit=-1" >> $PHP_INI_SCAN_DIR/10-core.ini \
    && echo "realpath_cache_size=4096k" >> $PHP_INI_SCAN_DIR/10-core.ini \
    && echo "realpath_cache_ttl=600" >> $PHP_INI_SCAN_DIR/10-core.ini \
    && echo "variables_order=EGPCS" >> $PHP_INI_SCAN_DIR/10-core.ini \

    # Configure php opcache
    && echo "### configure php-opcache" \
    && echo "opcache.enable=1" >> $PHP_INI_SCAN_DIR/10-opcache.ini \
    && echo "opcache.enable_cli=1" >> $PHP_INI_SCAN_DIR/10-opcache.ini \
    && echo "opcache.fast_shutdown=1" >> $PHP_INI_SCAN_DIR/10-opcache.ini \
    && echo "opcache.interned_strings_buffer=8" >> $PHP_INI_SCAN_DIR/10-opcache.ini \
    && echo "opcache.max_accelerated_files=20000" >> $PHP_INI_SCAN_DIR/10-opcache.ini \
    && echo "opcache.memory_consumption=256" >> $PHP_INI_SCAN_DIR/10-opcache.ini \
    && echo "opcache.validate_timestamps=0" >> $PHP_INI_SCAN_DIR/10-opcache.ini \

    # Configure php date
    && echo "### configure php-date" \
    && echo "date.timezone=${TIMEZONE}" >> $PHP_INI_SCAN_DIR/10-date.ini \

    # Use bash instead of ash
    && echo "### configure shell" \
    && sed -i "s#^/bin/ash#/bin/bash#g" /etc/passwd \

    # Permissions for custom scripts
    && echo "### fix permissions" \
    && chmod a+x /usr/bin/docker-entrypoint.sh \

    # Configure timezone
    && echo "### configure timezone" \
    && cp /usr/share/zoneinfo/${TIMEZONE} /etc/localtime \
    && echo "${TIMEZONE}" > /etc/timezone

RUN echo '### Install php extensions' \

    # Add build-dependencies
    && apk add --no-cache --virtual .build-deps \
        icu-dev \
        libxml2-dev \
        libzip-dev \
        ${PHPIZE_DEPS} \

    # Install php-extensions
    && docker-php-ext-install \
        intl \
        opcache \
        pcntl \
        sockets \
        zip \

    # https://github.com/docker-library/php/issues/1245#issuecomment-1020475243
    # && CFLAGS="$CFLAGS -D_GNU_SOURCE" docker-php-ext-install sockets \

    # Install php-pecl-extensions
    && echo '### Install pecl extensions' \
    && yes '' | pecl install xdebug \

    # Remove build-dependencies
    && apk del .build-deps \
    && rm -rf /var/cache/apk/*

COPY ./startup/*.sh /usr/bin/startup/
COPY ./functions/*.sh /usr/bin/functions/

# FPM specific

RUN ls \

    # FPM config
    && echo "### [fpm] configure fpm" \
    && chown -R www-data:www-data /app \
    && sed -i "s#^;log_limit = .*#log_limit = 8192#g" /usr/local/etc/php-fpm.conf \
    && sed -i "s#^;error_log =.*#error_log = /proc/self/fd/2#g" /usr/local/etc/php-fpm.conf \
    && sed -i "s#^listen =.*#listen = 9000#g" /usr/local/etc/php-fpm.d/www.conf \
    && sed -i "s#^;clear_env =.*#clear_env = no#g" /usr/local/etc/php-fpm.d/www.conf \
    && sed -i "s#^;catch_workers_output =.*#catch_workers_output = yes#g" /usr/local/etc/php-fpm.d/www.conf \
    && sed -i "s#^;decorate_workers_output =.*#decorate_workers_output = no#g" /usr/local/etc/php-fpm.d/www.conf \
    && sed -i "s#^;access\.log =.*#access.log = /proc/self/fd/1#g" /usr/local/etc/php-fpm.d/www.conf \
    && sed -i "s#^;php_admin_value\[error_log\].*#php_admin_value[error_log] = /proc/self/fd/2#g" /usr/local/etc/php-fpm.d/www.conf \
    && sed -i "s#^;php_admin_flag\[log_errors\].*#php_admin_flag[log_errors] = on#g" /usr/local/etc/php-fpm.d/www.conf \
    && printf "\nphp_admin_value[memory_limit] = 128M\n" >> /usr/local/etc/php-fpm.d/www.conf \

    # PHP config
    && echo "### [fpm] configure php-fpm" \
    && echo "post_max_size=200M" >> /usr/local/etc/php/conf.d/20-core.ini \
    && echo "upload_max_filesize=200M" >> /usr/local/etc/php/conf.d/20-core.ini \

    # Cleanup
    && echo "### [fpm] cleanup" \
    && rm -rf /var/cache/apk/*

EXPOSE 9000

ENTRYPOINT ["/usr/bin/docker-entrypoint.sh"]

CMD ["php-fpm"]
