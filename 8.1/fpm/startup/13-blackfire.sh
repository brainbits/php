#!/bin/bash
set -e

if [ -n "$PHP_ACTIVATE_BLACKFIRE" ] && [ "$PHP_ACTIVATE_BLACKFIRE" != "0" ]; then
    activate_blackfire 05

    if [ -z "$PHP_BLACKFIRE_SOCKET" ]; then
        PHP_BLACKFIRE_SOCKET=tcp://blackfire:8307
    fi

    blackfire_agent_socket $PHP_BLACKFIRE_SOCKET 15
elif [ -n "$PHP_ACTIVATE_BLACKFIRE" ] && [ "$PHP_ACTIVATE_BLACKFIRE" == "0" ]; then
    deactivate_blackfire 05
fi
