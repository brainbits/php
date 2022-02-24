#!/bin/bash
set -e

function as_apache {
    log_deprecation "as_apache is deprecated, use as_user"
    su -s /bin/bash apache -c "$1"
}

function as_user {
    su -s /bin/bash apache -c "$1"
}
