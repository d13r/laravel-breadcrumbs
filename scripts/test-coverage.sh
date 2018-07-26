#!/bin/bash
set -o nounset -o pipefail -o errexit
cd "$(dirname "$0")/.."

################################################################################
# Run PHPUnit and generate code coverage report (requires Xdebug).
################################################################################

if [ ! -d vendor ]; then
    composer install
fi

for executable in php7.2 php7.1; do
    if command -v $executable >/dev/null 2>&1; then
        exec $executable -d xdebug.coverage_enable=On vendor/bin/phpunit --coverage-html test-coverage/ "$@"
    fi
done

if ! $has_run; then
    exec php -d xdebug.coverage_enable=On vendor/bin/phpunit --coverage-html test-coverage/ "$@"
fi
