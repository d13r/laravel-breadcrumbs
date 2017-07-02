<?php

Breadcrumbs::register('multiple-file-test', function ($breadcrumbs) {
    $breadcrumbs->parent('multiple-file-test-parent');
    $breadcrumbs->push('Loaded');
});
