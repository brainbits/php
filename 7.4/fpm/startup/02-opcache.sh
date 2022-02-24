#!/bin/bash
set -e

function opcache_set_enable {
    php_ini_set opcache.enable $1 $2 opcache
}

function opcache_set_enable_cli {
    php_ini_set opcache.enable_cli $1 $2 opcache
}

function opcache_set_validate_timestamps {
    php_ini_set opcache.validate_timestamps $1 $2 opcache
}

function opcache_set_error_log {
    php_ini_set opcache.error_log $1 $2 opcache
}
