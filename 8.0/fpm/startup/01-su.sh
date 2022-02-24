#!/bin/bash
set -e

function as_nobody {
    log_deprecation "as_nobody is deprecated, use as_user"
    su -s /bin/bash nobody -c "$1"
}

function as_user {
    su -s /bin/bash nobody -c "$1"
}
