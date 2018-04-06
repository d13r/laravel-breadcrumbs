#!/bin/bash
set -o nounset -o pipefail -o errexit
cd "$(dirname "$0")/.."

################################################################################
# Run PHPUnit.
################################################################################

if [ ! -d vendor ]; then
    scripts/composer.sh install
fi

scripts/docker-compose.sh run --rm phpunit-72 vendor/bin/phpunit "$@"
echo
scripts/docker-compose.sh run --rm phpunit-71 vendor/bin/phpunit "$@"
