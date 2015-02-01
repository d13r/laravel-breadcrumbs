################################################################################
 Contributing
################################################################################

.. only:: html

    .. contents::
        :local:


To submit an improvement to the documentation, simply click the link at the top of the page to edit the file on GitHub. This will automatically be turned into a pull request.

If you want to submit a bug fix, the information below may help you to get started. Make your changes in a new branch, based on the ``develop`` branch, then simply open a `pull request <https://github.com/davejamesmiller/laravel-breadcrumbs/pulls>`_ on GitHub.

If you want to submit a new feature, you may want to open an `issue <https://github.com/davejamesmiller/laravel-breadcrumbs/issues>`_ to discuss the idea first, to make sure it will be accepted. (Or you can go ahead and develop it first if you prefer!)


================================================================================
 Developing against a real application
================================================================================

The easiest way to develop Laravel Breadcrumbs alongside a real Laravel application is to set it up as normal, but tell Composer to install from source with the ``--prefer-source`` flag.

If you've already got it installed, delete it from the ``vendor/`` directory and re-install from source:

.. code-block:: bash

    $ cd /path/to/repo
    $ rm -rf vendor/davejamesmiller/laravel-breadcrumbs
    $ composer install --prefer-source
    $ cd vendor/davejamesmiller/laravel-breadcrumbs
    $ git checkout develop
    $ git checkout -b YOUR_BRANCH
    # Make changes and commit them
    $ git remote add YOUR_USERNAME git@github.com:my-username/laravel-breadcrumbs
    $ git push -u YOUR_USERNAME YOUR_BRANCH

There is also a `test app <https://github.com/davejamesmiller/laravel-breadcrumbs-test>`_ available to simplify testing against multiple versions of Laravel.

.. note::

    The test app is not a replacement for unit tests - we have those too - but it gives a better feel for how the package works in practice.


================================================================================
 Using your fork in a project
================================================================================

If you have forked the package (e.g. to fix a bug or add a feature), you may want to use that version in your project until the changes are merged and released. To do that, simply update the ``composer.json`` in your main project as follows:

.. code-block:: json

    {
        "repositories": [
            {
                "type": "vcs",
                "url": "https://github.com/YOUR_USERNAME/laravel-breadcrumbs.git"
            }
        ],
        "require": {
            "davejamesmiller/laravel-breadcrumbs": "dev-YOUR_BRANCH"
        }
    }

Replace ``YOUR_USERNAME`` with your GitHub username and ``YOUR_BRANCH`` with the branch name (e.g. ``develop``). This tells Composer to use your repository instead of the default one.


================================================================================
 Unit tests
================================================================================

To run the unit tests, simply `install PHP Unit <http://phpunit.de/manual/current/en/installation.html>`_ and run:

.. code-block:: bash

    $ cd /path/to/laravel-breadcrumbs
    $ composer update --dev
    $ phpunit

(Note: The unit tests are not 100% complete yet, and the code will probably need some refactoring to make it easier to test.)


================================================================================
 Code coverage in unit tests
================================================================================

To check code coverage, you will also need `Xdebug <http://xdebug.org/>`_ installed. Run:

.. code-block:: bash

    $ cd /path/to/laravel-breadcrumbs
    $ composer update --dev
    $ php -d xdebug.coverage_enable=On `which phpunit` --coverage-html test-coverage

Then open ``test-coverage/index.html`` to view the results. However, be aware of the `edge cases <http://phpunit.de/manual/current/en/code-coverage-analysis.html#code-coverage-analysis.edge-cases>`_ in PHPUnit.


================================================================================
 Releasing a new version
================================================================================

*This is a reference for me:*

- Ensure the documentation is up to date, particularly the :doc:`changelog`
- Merge into ``master`` (``git checkout master; git merge develop``)
- Push the code changes (``git push``)
- Double-check the `Travis CI results <https://travis-ci.org/davejamesmiller/laravel-breadcrumbs>`_
- Tag the release (``git tag 1.2.3``)
- Push the tag (``git push --tag``)
