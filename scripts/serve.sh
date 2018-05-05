#!/bin/bash
set -o nounset -o pipefail -o errexit
cd "$(dirname "$0")/.."

################################################################################
# Run web server.
################################################################################

# Install test app dependencies
if [ ! -L test-app/vendor/davejamesmiller/laravel-breadcrumbs ]; then
    # In case the directory exists but isn't a symlink for some reason, remove it
    rm -rf test-app/vendor/davejamesmiller/laravel-breadcrumbs
fi

if [ ! -d test-app/vendor/davejamesmiller/laravel-breadcrumbs ]; then
    (cd test-app && ../scripts/composer.sh install)
fi

# Display URLs
echo
echo "  README:         http://$(hostname -f):8000/"
echo "  API Docs:       http://$(hostname -f):8001/  (run 'scripts/phpdoc.sh' to build)"
echo "  Test Coverage:  http://$(hostname -f):8002/  (run 'scripts/test-coverage.sh' to build)"
echo "  PHP 7.1:        http://$(hostname -f):8071/"
echo "  PHP 7.2:        http://$(hostname -f):8072/"
echo
echo "Press Ctrl+C to stop."
echo

# Run web servers
scripts/docker-compose.sh up readme api-docs test-coverage test-app-71 test-app-72
