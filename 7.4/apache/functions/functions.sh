#!/usr/bin/env bash
function apache_php_enable_opcache_reset() {
  target=$1
  uri=$2

  printf "#!/usr/bin/env bash\n\necho \"<?php opcache_reset();\" > \"$target\"\ncurl \"$uri\"\nrm \"$target\"\n" > /usr/local/bin/opcache_reset.sh
  chmod +x /usr/local/bin/opcache_reset.sh
}
function apache_set_document_root() {
  sed -i "s#^DocumentRoot .*#DocumentRoot \"$1\"#g" /etc/apache2/httpd.conf
}
function apache_set_listen() {
  sed -i "s#^Listen 0.0.0.0:80#Listen $1#g" /etc/apache2/httpd.conf
}
function apache_set_php_memory_limit() {
  sed -i "s#^php_value memory_limit 128M#php_value memory_limit $1#g" /etc/apache2/conf.d/php7-module.conf
}
function apache_document_root() {
  echo "apache_document_root() is deprecated, use apache_set_document_root()" 1>&2
  apache_set_document_root $1
}
function php_enable_opcache_reset() {
  echo "php_enable_opcache_reset() is deprecated, use apache_php_enable_opcache_reset()" 1>&2

  target=$1
  uri=$2

  printf "#!/usr/bin/env bash\n\necho \"<?php opcache_reset();\" > \"$target\"\ncurl \"$uri\"\nrm \"$target\"\n" > /usr/local/bin/opcache_reset.sh
  chmod +x /usr/local/bin/opcache_reset.sh
}
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
