#!/bin/bash
set -o nounset -o pipefail -o errexit
cd "$(dirname "$0")/.."

################################################################################
# Run phpDocumentor.
################################################################################

# Can't use Composer because there is a conflict between phpDocumentor and one
# of the other Laravel Breadcrumbs dependencies
[ -d docs/bin ] || mkdir -p docs/bin
[ -f docs/bin/phpdoc ] || curl https://www.phpdoc.org/phpDocumentor.phar > docs/bin/phpdoc
[ -x docs/bin/phpdoc ] || chmod +x docs/bin/phpdoc

exec docs/bin/phpdoc "$@"
