#!/bin/bash
set -e

function activate_blackfire {
    log_setting "create /usr/local/etc/php/conf.d/$1_blackfire.ini | + extension=blackfire"
    echo extension=blackfire.so > /usr/local/etc/php/conf.d/$1_blackfire.ini
}

function deactivate_blackfire {
    log_setting "remove /usr/local/etc/php/conf.d/$1_blackfire.ini | - extension=blackfire"
    if [ -f /usr/local/etc/php/conf.d/$1_blackfire.ini ] ; then
        rm /usr/local/etc/php/conf.d/$1_blackfire.ini
    fi
}

function blackfire_agent_socket {
    php_ini_set blackfire.agent_socket $1 $2 blackfire
}
