#!/bin/bash
set -e

if [ -n "$COMPOSER_INSTALL_DEV" ]; then
    composer_install_dev
fi
