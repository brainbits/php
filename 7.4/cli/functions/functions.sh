#!/usr/bin/env bash
function php_memory_limit() {
  echo "php_memory_limit() is deprecated, use apache_set_php_memory_limit()" 1>&2
  apache_set_php_memory_limit $1
}
function php_set_timezone() {
  echo "date.timezone=$1" >> /etc/php7/conf.d/90-date.ini
}
function system_set_timezone() {
  apk --no-cache add tzdata
  cp /usr/share/zoneinfo/$1 /etc/localtime
  echo "$1" > /etc/timezone
  apk --no-cache del tzdata
}
