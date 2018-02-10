#!/bin/bash
set -o nounset -o pipefail -o errexit
cd "$(dirname "$0")/.."

################################################################################
# Run PHPUnit.
################################################################################

if which php7.1 >/dev/null 2>&1; then
    php=php7.1
else
    php=php
fi

exec $php vendor/bin/phpunit "$@"
