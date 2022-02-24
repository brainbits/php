#!/bin/bash
set -e

function composer_install_dev {
    log_command "composer install --no-ansi --no-interaction --optimize-autoloader --prefer-dist $*"
    # composer install as user
    as_user "composer install --no-ansi --no-interaction --optimize-autoloader --prefer-dist $*"
}

function composer_global_require {
    # explicitly disable cache to prevent ownership problems due to as_user above
    log_command "composer global require --no-cache $*"
    composer global require --no-cache $*
}
