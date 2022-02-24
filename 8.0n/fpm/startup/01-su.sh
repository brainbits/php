#!/bin/bash
set -e

function as_user {
    su -s /bin/bash www-data -c "$1"
}
