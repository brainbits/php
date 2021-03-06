#!/usr/bin/env bash
function php_set_timezone() {
  echo "date.timezone=$1" >> /usr/local/etc/php/conf.d/90-date.ini
}
function system_set_timezone() {
  apk --no-cache add tzdata
  cp /usr/share/zoneinfo/$1 /etc/localtime
  echo "$1" > /etc/timezone
  apk --no-cache del tzdata
}
