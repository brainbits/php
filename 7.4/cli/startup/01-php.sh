#!/bin/bash
set -e

function php_ini_set {
    log_setting "/etc/php7/conf.d/$3_$4.ini | +$1=$2"
    echo "$1=$2" >> /etc/php7/conf.d/$3_$4.ini
}
