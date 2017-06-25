#!/bin/bash
set -o nounset -o pipefail -o errexit
cd "$(dirname "$0")/.."

################################################################################
# Run PHPUnit with code coverage (requires Xdebug).
################################################################################

if which php7.0 >/dev/null 2>&1; then
    php=php7.0
else
    php=php
fi

exec $php -d xdebug.coverage_enable=On vendor/bin/phpunit --coverage-html test-coverage "$@"
