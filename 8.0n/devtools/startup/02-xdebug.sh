#!/bin/bash
set -e

function activate_xdebug {
log_setting "create /usr/local/etc/php/conf.d/$1_xdebug.ini | + zend_extension=xdebug"
echo zend_extension=xdebug > /usr/local/etc/php/conf.d/$1_xdebug.ini
}

function deactivate_xdebug {
log_setting "remove /usr/local/etc/php/conf.d/$1_xdebug.ini | - zend_extension=xdebug"
if [ -f /usr/local/etc/php/conf.d/$1_xdebug.ini ] ; then
rm /usr/local/etc/php/conf.d/$1_xdebug.ini
fi
}

function xdebug_remote_enable {
php_ini_set xdebug.mode debug $2 xdebug
}

function xdebug_remote_autostart {
php_ini_set xdebug.start_with_request $1 $2 xdebug
}

function xdebug_remote_host {
php_ini_set xdebug.client_host $1 $2 xdebug
}

function xdebug_remote_log {
php_ini_set xdebug.log $1 $2 xdebug
}

function xdebug_remote_port {
php_ini_set xdebug.client_port $1 $2 xdebug
}

function xdebug_remote_connect_back {
php_ini_set xdebug.discover_client_host $1 $2 xdebug
}
