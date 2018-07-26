#!/bin/bash
set -o nounset -o pipefail -o errexit
cd "$(dirname "$0")/.."

################################################################################
# Run PHPUnit.
################################################################################

if [ ! -d vendor ]; then
    composer install
fi

has_run=false

for executable in php7.2 php7.1; do
    if command -v $executable >/dev/null 2>&1; then
        $executable vendor/bin/phpunit "$@"
        has_run=true
    fi
done

if ! $has_run; then
    php vendor/bin/phpunit "$@"
fi
