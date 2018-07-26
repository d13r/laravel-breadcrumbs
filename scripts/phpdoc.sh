#!/bin/bash
set -o nounset -o pipefail -o errexit
cd "$(dirname "$0")/.."

################################################################################
# Run phpDocumentor.
################################################################################

# Can't use Composer because there is a conflict between phpDocumentor and one
# of the other Laravel Breadcrumbs dependencies
[ -d api-docs/bin ] || mkdir -p api-docs/bin
[ -f api-docs/bin/phpdoc ] || curl https://www.phpdoc.org/phpDocumentor.phar > api-docs/bin/phpdoc
[ -x api-docs/bin/phpdoc ] || chmod +x api-docs/bin/phpdoc

exec api-docs/bin/phpdoc "$@"
