#!/usr/bin/env bash
function php_memory_limit() {
  echo "php_memory_limit() is deprecated, use apache_set_php_memory_limit()" 1>&2
  apache_set_php_memory_limit $1
}
function fpm_php_enable_opcache_reset() {
  printf "#!/usr/bin/env bash\n\nkillall -SIGUSR2 php-fpm\n" > /usr/local/bin/opcache_reset.sh
  chmod +x /usr/local/bin/opcache_reset.sh
}
function fpm_set_php_memory_limit() {
  sed -i "s#^php_admin_value\[memory_limit\] = 128M#php_admin_value[memory_limit] = $1#g" /etc/php8/php-fpm.d/www.conf
}
function php_set_timezone() {
  echo "date.timezone=$1" >> /etc/php8/conf.d/90-date.ini
}
function system_set_timezone() {
  apk --no-cache add tzdata
  cp /usr/share/zoneinfo/$1 /etc/localtime
  echo "$1" > /etc/timezone
  apk --no-cache del tzdata
}
