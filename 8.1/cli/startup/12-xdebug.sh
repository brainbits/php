#!/bin/bash
set -e

if [ -n "$PHP_ACTIVATE_XDEBUG" ] && [ "$PHP_ACTIVATE_XDEBUG" != "0" ]; then
    activate_xdebug 05

    xdebug_remote_autostart off 15
    xdebug_remote_enable 1 15
    xdebug_remote_port 9000 15
    xdebug_remote_connect_back 0 15

    if [ -n "$PHP_XDEBUG_REMOTE_LOG" ] && [ "$PHP_XDEBUG_REMOTE_LOG" != "0" ]; then
        if [ "$PHP_XDEBUG_REMOTE_LOG" == "1" ]; then
            xdebug_remote_log /tmp/xdebug.log 15
        else
            xdebug_remote_log $PHP_XDEBUG_REMOTE_LOG 15
        fi
    fi

    if [ -n "$PHP_XDEBUG_REMOTE_HOST" ]; then
        xdebug_remote_host $PHP_XDEBUG_REMOTE_HOST 15
    else
        xdebug_remote_host host.docker.internal 15
    fi

    set_env COMPOSER_ALLOW_XDEBUG 1
elif [ -n "$PHP_ACTIVATE_XDEBUG" ] && [ "$PHP_ACTIVATE_XDEBUG" == "0" ]; then
    deactivate_xdebug 05
fi
