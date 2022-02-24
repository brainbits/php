#!/bin/bash
set -e

if [ -n "$PHP_OPCACHE_ENABLE" ]; then
    opcache_set_enable $PHP_OPCACHE_ENABLE 15
fi

if [ -n "$PHP_OPCACHE_ENABLE_CLI" ]; then
    opcache_set_enable_cli $PHP_OPCACHE_ENABLE_CLI 15
fi

if [ -n "$PHP_OPCACHE_VALIDATE_TIMESTAMPS" ]; then
    opcache_set_validate_timestamps $PHP_OPCACHE_VALIDATE_TIMESTAMPS 15
fi

opcache_set_error_log /tmp/opcache.log 15
