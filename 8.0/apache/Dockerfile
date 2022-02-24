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

# Apache specific

RUN echo "### [apache] install packages" \
    && apk --no-cache add \
        dumb-init \
        apache2 \
        php8-apache2 \

    # Apache config
    && echo "### [apache] configure apache" \
    && chown -R apache:apache /app \
    && mkdir -p /run/apache2 \
    && ln -sf /dev/stdout /var/log/apache2/access.log \
    && ln -sf /dev/stderr /var/log/apache2/error.log \
    && sed -i "s#^User .*#User \"apache\"#g" /etc/apache2/httpd.conf \
    && sed -i "s#^Group .*#Group \"apache\"#g" /etc/apache2/httpd.conf \
    && sed -i "s#^DocumentRoot \".*#DocumentRoot \"/app\"#g" /etc/apache2/httpd.conf \
    && sed -i "s#AllowOverride none#AllowOverride All#gi" /etc/apache2/httpd.conf \
    && sed -i "s#Directory \"/var/www/localhost/htdocs.*#Directory \"/app\" >#g" /etc/apache2/httpd.conf \
    && sed -i "s#\#LoadModule rewrite_module modules/mod_rewrite.so#LoadModule rewrite_module modules/mod_rewrite.so#g" /etc/apache2/httpd.conf \
    && sed -i "/#ServerName.*/c\ServerName localhost" /etc/apache2/httpd.conf \
    && sed -i "s#^Listen 80#Listen 0.0.0.0:80#g" /etc/apache2/httpd.conf \
    && sed -i "s#^LoadModule php8_module#Loadmodule php_module#g" /etc/apache2/conf.d/php8-module.conf \
    && printf "\nphp_value memory_limit 128M\n" >> /etc/apache2/conf.d/php8-module.conf \

    # PHP config
    && echo "### [apache] configure php-apache" \
    && echo "post_max_size=200M" >> /etc/php8/conf.d/20-core.ini \
    && echo "upload_max_filesize=200M" >> /etc/php8/conf.d/20-core.ini \

    # Cleanup
    && echo "### [apache] cleanup" \
    && rm -rf /var/cache/apk/*

EXPOSE 80

ENTRYPOINT ["/usr/bin/docker-entrypoint.sh"]

CMD ["/usr/sbin/httpd", "-D", "FOREGROUND"]
