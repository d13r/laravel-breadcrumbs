<?php

Breadcrumbs::for('multiple-file-test', function ($trail) {
    $trail->parent('multiple-file-test-parent');
    $trail->push('Loaded');
});
