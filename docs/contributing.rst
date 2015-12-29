################################################################################
 Contributing
################################################################################

.. NOTE: This text is also in ../README.rst

If you want to submit a **bug fix**, please make your changes in a new branch, then open a `pull request <https://github.com/davejamesmiller/laravel-breadcrumbs/pulls>`_. (The `Contributing page of the docs <http://laravel-breadcrumbs.davejamesmiller.com/en/latest/contributing.html>`_ may help you to get started if you've not done this before.)

If you want to submit a **new feature**, it's usually best to open an `issue <https://github.com/davejamesmiller/laravel-breadcrumbs/issues>`_ to discuss the idea first -- to make sure it will be accepted before spending too much time on it. (Of course you can go ahead and develop it first if you prefer!) Please be sure to include unit tests and update the documentation as well.

If you have any suggestions for improving the **documentation** -- especially if anything is unclear to you and could be explained better -- please let me know. (Or just edit it yourself and make a pull request.)

.. only:: html

    .. contents::
        :local:


================================================================================
 Developing inside a real application
================================================================================

The easiest way to develop Laravel Breadcrumbs alongside a real Laravel application is to set it up as normal, but tell Composer to install from source with the ``--prefer-source`` flag.

If you've already got it installed, delete it from the ``vendor/`` directory and re-install from source:

.. code-block:: bash

    $ cd /path/to/repo
    $ rm -rf vendor/davejamesmiller/laravel-breadcrumbs
    $ composer install --prefer-source
    $ cd vendor/davejamesmiller/laravel-breadcrumbs
    $ git checkout -t origin/master
    $ git checkout -b YOUR_BRANCH
    # Make changes and commit them
    $ git remote add YOUR_USERNAME git@github.com:YOUR_USERNAME/laravel-breadcrumbs
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

To run the unit tests, simply run:

.. code-block:: bash

    $ cd /path/to/laravel-breadcrumbs
    $ composer update
    $ ./test.sh

(Note: The unit tests are not 100% complete yet, and the code will probably need some refactoring to make it easier to test.)


----------------------------------------
 Code coverage
----------------------------------------

To check code coverage, you will also need `Xdebug <http://xdebug.org/>`_ installed. Run:

.. code-block:: bash

    $ ./test-coverage.sh

Then open ``test-coverage/index.html`` to view the results. (However, be aware of the `edge cases <https://phpunit.de/manual/current/en/code-coverage-analysis.html#code-coverage-analysis.edge-cases>`_ in PHPUnit that can make it not-quite-accurate.)


.. _contributing-documentation:

================================================================================
 Documentation
================================================================================

Documentation is in ``docs/``. It is written in `reStructuredText <http://docutils.sourceforge.net/rst.html>`_ and converted to HTML and PDF formats by `Sphinx <http://sphinx-doc.org/>`_.

To submit a documentation change, simply `edit the appropriate file on GitHub <https://github.com/alberon/awe/tree/master/docs>`_. (There's an "Edit on GitHub" link in the top-right corner of each page.)

.. warning::

    Not all markup is supported by GitHub -- e.g. ``:ref:`` and ``:doc:`` -- so the preview may not be exactly what appears in the online documentation.

For more comprehensive documentation changes you may be better installing Sphinx so you can test the docs locally:


----------------------------------------
 Installing Sphinx
----------------------------------------

You will need `Python <https://www.python.org/>`_ and `pip <https://pypi.python.org/pypi/pip>`_ to install `Sphinx <http://sphinx-doc.org/>`_, the documentation generator. To install them (on Debian Wheezy or similar), you can run the following:

.. code-block:: bash

    $ sudo apt-get install python python-pip
    $ sudo pip install sphinx sphinx-autobuild sphinx_rtd_theme

To build the PDF documentation, you will also need LaTeX installed:

.. code-block:: bash

    $ sudo apt-get install texlive texlive-latex-extra


----------------------------------------
 Building documentation
----------------------------------------

To build the HTML docs (``docs-html/index.html``):

.. code-block:: bash

    $ ./build-html-docs.sh

This will build the docs and run a HTML server on port 8000 that will automatically rebuild the docs and reload the page whenever you modify a file.

To build the PDF docs (``docs-pdf/laravel-breadcrumbs.pdf``):

.. code-block:: bash

    $ ./build-pdf-docs.sh


----------------------------------------
 Sphinx markup reference
----------------------------------------

I found the following documents useful when writing the documentation:

- `reStructuredText quick reference <http://docutils.sourceforge.net/docs/user/rst/quickref.html>`_
- `Admonitions list <http://docutils.sourceforge.net/docs/ref/rst/directives.html#admonitions>`_ (``note::``, ``warning::``, etc.)
- `Code examples markups <http://sphinx-doc.org/markup/code.html>`_ (``code-block::``, ``highlight::``)
- `Other paragraph-level markup <http://sphinx-doc.org/markup/para.html>`_ (``versionadded::``, ``deprecated::``, etc.)
- `Inline markup <http://sphinx-doc.org/markup/inline.html>`_ (``:ref:``, ``:doc:``, etc.)
- `Table of contents <http://sphinx-doc.org/markup/toctree.html>`_ (``toctree::``)


----------------------------------------
 Heading styles
----------------------------------------

The following code styles are used for headings::

    ################################################################################
     Page title (80 hashes)
    ################################################################################

    ================================================================================
     Section title (80 equals signs)
    ================================================================================

    ----------------------------------------
     Heading 2 (40 hypens)
    ----------------------------------------

    Heading 3 (full stops)
    ......................
