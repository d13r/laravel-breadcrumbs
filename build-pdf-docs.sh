#!/bin/bash
set -eu
cd $(dirname $0)

rm -rf docs-pdf/
sphinx-build -b latex docs/ docs-pdf/
make -C docs-pdf/ all-pdf
