#!/bin/bash
set -e

function composer_install_dev {
    log_command "composer install --no-ansi --no-interaction --optimize-autoloader --prefer-dist $*"
    composer install --no-ansi --no-interaction --optimize-autoloader --prefer-dist $*
}

function composer_global_require {
    log_command "composer global require $*"
    composer global require $*
}
