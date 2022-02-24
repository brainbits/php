#!/bin/bash
set -e

function php_ini_set {
    log_setting "/usr/local/etc/php/conf.d/$3_$4.ini | +$1=$2"
    echo "$1=$2" >> /usr/local/etc/php/conf.d/$3_$4.ini
}
