################################################################################
 Support
################################################################################

Please submit issues, pull requests and support requests using `GitHub issues <https://github.com/davejamesmiller/laravel-breadcrumbs/issues>`_.

.. note::

    Don't be afraid to go into the Laravel Breadcrumbs code and use ``var_dump()`` (or ``print_r()``) to see what's happening and try to fix your own problems! A `pull request <contributing>`_ or detailed bug report is much more likely to get attention than a vague error report. Also make sure you read the documentation carefully.


================================================================================
 Submitting a bug report
================================================================================

Bug reports should include the following:

- The complete error message, including file & line numbers
- Steps to reproduce the problem
- Laravel Breadcrumbs version (should be the latest version)
- Laravel version
- PHP version
- The ``providers`` and ``aliases`` sections of ``config/app.php`` (Note: **not the Encryption Key section** which should be kept private) - in case there's a conflict with another package

You may also need to include copies of:

- ``app/Http/breadcrumbs.php``
- ``config/breadcrumbs.php`` (if used)
- The view or layout that outputs the breadcrumbs
- The custom breadcrumbs template (if applicable)
- Any other relevant files

If you have any suggestions for improving the documentation - especially if anything is unclear to you and could be explained better - please let me know.
