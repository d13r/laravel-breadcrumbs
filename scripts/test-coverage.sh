#!/bin/bash
set -o nounset -o pipefail -o errexit
cd "$(dirname "$0")/.."

################################################################################
# Run PHPUnit and generate code coverage report.
################################################################################

if [ ! -d vendor ]; then
    scripts/composer.sh install
fi

scripts/docker-compose.sh run --rm phpunit-72 -d xdebug.coverage_enable=On vendor/bin/phpunit --coverage-html test-coverage "$@"
