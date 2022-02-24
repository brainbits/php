#!/bin/bash
set -e

function activate_blackfire {
    log_setting "create /etc/php7/conf.d/$1_blackfire.ini | + extension=blackfire"
    echo extension=blackfire.so > /etc/php7/conf.d/$1_blackfire.ini
}

function deactivate_blackfire {
    log_setting "remove /etc/php7/conf.d/$1_blackfire.ini | - extension=blackfire"
    if [ -f /etc/php7/conf.d/$1_blackfire.ini ] ; then
        rm /etc/php7/conf.d/$1_blackfire.ini
    fi
}

function blackfire_agent_socket {
    php_ini_set blackfire.agent_socket $1 $2 blackfire
}
