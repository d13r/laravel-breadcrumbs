#!/bin/bash
set -eu
cd $(dirname $0)

vendor/bin/phpunit "$@"
