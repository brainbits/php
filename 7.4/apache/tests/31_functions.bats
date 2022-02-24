#!/usr/bin/env bats
set -e

setup() {
  if [ -z ${TAG+x} ]; then
    echo 'TAG environment variable not set'
    exit 1
  fi
}

@test "apache_set_document_root() sets document root" {
  run docker run --rm $TAG bash -c 'source /usr/bin/functions/functions.sh && apache_set_document_root /tmp && grep "^DocumentRoot " /etc/apache2/httpd.conf'
  echo "status: $status"
  echo "output: $output"
  [ "$status" -eq 0 ]
  [[ "$output" == "DocumentRoot \"/tmp\"" ]]
}

@test "apache_set_listen() sets listen" {
  run docker run --rm $TAG bash -c 'source /usr/bin/functions/functions.sh && apache_set_listen 1.2.3.4:8080 && grep "^Listen " /etc/apache2/httpd.conf'
  echo "status: $status"
  echo "output: $output"
  [ "$status" -eq 0 ]
  [[ "$output" == "Listen 1.2.3.4:8080" ]]
}

@test "php_set_memory_limit() sets memory limit" {
  run docker run --rm $TAG bash -c 'source /usr/bin/functions/functions.sh && apache_set_php_memory_limit 50M && grep memory_limit /etc/apache2/conf.d/php7-module.conf'
  echo "status: $status"
  echo "output: $output"
  [ "$status" -eq 0 ]
  [[ "$output" == "php_value memory_limit 50M" ]]
}
