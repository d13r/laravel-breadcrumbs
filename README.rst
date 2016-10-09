################################################################################
 Laravel Breadcrumbs 3
################################################################################

A simple Laravel-style way to create breadcrumbs in `Laravel 5 <http://laravel.com/>`_.

(If you are still using Laravel 4.0 to 4.2 please use the `2.x version <https://github.com/davejamesmiller/laravel-breadcrumbs/tree/2.x>`_ of Laravel Breadcrumbs instead.)

================================================================================
 THIS PACKAGE IS NO LONGER MAINTAINED
================================================================================

**Short Version**

- You're welcome to keep using Laravel Breadcrumbs, but
- No support requests will be answered
- No bugs will be fixed
- No pull requests will be merged
- Feel free to fork it and maintain it yourself

**Why?**

I originally built Laravel Breadcrumbs in May 2013 when I was just learning Laravel (Laravel 4 was still in beta at the time). I decided to release it as a package mostly just to learn how packages worked in more detail.

Over time it became reasonably popular (381k installs, 757 stars, Page 1 of `Packalyst Most Popular <http://packalyst.com/packages>`_), and people started opening issues requesting support and additional features. I've always tried to be helpful and provide support, and for the last 18 months I have been planning to dedicate some time to merging all the open pull requests, implementing the feature requests and updating/rewriting the documentation... But finally I realised I no longer have the motivation needed for it.

So rather than quietly abandoning it and leaving users wondering what happened, I've decided to officially announce that the package is no longer maintained.

(To be clear, you are welcome to keep using it - just don't expect me to fix it if it breaks. That includes when new Laravel versions are relased.)

**Forking It**

Laravel Breadcrumbs is released under the `MIT License <https://laravel-breadcrumbs.readthedocs.io/en/latest/license.html>`_, which means you are free to create your own fork (whether for your own use or for anyone to use) as long as you retain the copyright notices.

The `documentation <https://laravel-breadcrumbs.readthedocs.io/en/latest/contributing.html>`_ includes some instructions that may help if you haven't done that before.

I'm not planning to hand over control of this repo to anyone else (as it is in my name), but if anyone decides to create a fork that they are willing to support and maintain, please `email me <mailto:dave@davejamesmiller.com>`_ and I'll add a link to this page. You can also use `issue #137 <https://github.com/davejamesmiller/laravel-breadcrumbs/issues/137>`_ to discuss it among yourselves.

If you do commit to supporting your fork, you should be aware that writing code to solve your own problems is the easy bit - the tricky bits include:

- Dealing with `vague support requests <https://github.com/davejamesmiller/laravel-breadcrumbs/issues/71>`_ and `people that expect you to solve their bugs with no thanks <https://github.com/davejamesmiller/laravel-breadcrumbs/issues/107>`_
- Being asked to write `new features that you don't personally need <https://github.com/davejamesmiller/laravel-breadcrumbs/issues/84>`_ because other people want them but aren't willing to spend time writing them
- Writing clear and concise documentation `for every feature <https://github.com/davejamesmiller/laravel-breadcrumbs/issues/134#issuecomment-246403506>`_, so users don't get confused and take up even more time asking for support
- Having to write most of the documentation and unit tests yourself because `few <https://github.com/davejamesmiller/laravel-breadcrumbs/pull/74>`_ `PRs <https://github.com/davejamesmiller/laravel-breadcrumbs/pull/82>`_ `include <https://github.com/davejamesmiller/laravel-breadcrumbs/pull/83>`_ `them <https://github.com/davejamesmiller/laravel-breadcrumbs/pull/130>`_
- Doing all of that even though you don't get paid anything for it

**Known Bugs**

I'm only aware of 1 outstanding bug:

- `An exception is thrown when you use route-bound breadcrumbs in unnamed routes <https://github.com/davejamesmiller/laravel-breadcrumbs/issues/133>`_.

As a workaround, either name your routes or wrap it in a try/catch block.

**Open Pull Requests**

Thanks to the people who opened these PRs. I'm sorry I won't be able to merge them. Maybe if someone creates a fork they would be good enough to review them:

- `Allow breadcrumb name to be set in routes <https://github.com/davejamesmiller/laravel-breadcrumbs/pull/74>`_
- `Add Breadcrumbs::get() <https://github.com/davejamesmiller/laravel-breadcrumbs/pull/82>`_ method
- Microdata in templates - `Option 1 <https://github.com/davejamesmiller/laravel-breadcrumbs/pull/83/files>`_, `Option 2 <https://github.com/davejamesmiller/laravel-breadcrumbs/pull/124>`_
- `Bootstrap 4 template <https://github.com/davejamesmiller/laravel-breadcrumbs/issues/128>`_
- `Support for class-based breadcrumbs (alternative to closures) <https://github.com/davejamesmiller/laravel-breadcrumbs/pull/129>`_
- `Make breadcrumbs location configurable <https://github.com/davejamesmiller/laravel-breadcrumbs/pull/130>`_
- `Materialize and Foundation 6 templates <https://github.com/davejamesmiller/laravel-breadcrumbs/pull/131>`_

**Open Requests**

- `Automatically add a breadcrumb for pagination <https://github.com/davejamesmiller/laravel-breadcrumbs/issues/86>`_
- `Support dependency injection <https://github.com/davejamesmiller/laravel-breadcrumbs/issues/126>`_
- `Improve documentation around multiple parameters <https://github.com/davejamesmiller/laravel-breadcrumbs/issues/134>`_
- `Add Breadcrumbs::group() method <https://github.com/davejamesmiller/laravel-breadcrumbs/issues/84>`_ - though I don't personally see the value
- `Cache breadcrumbs <https://github.com/davejamesmiller/laravel-breadcrumbs/issues/112>`_ - though I'm not convinced there would be much speed improvement
- `Throw an exception if accidentally called recursively <https://github.com/davejamesmiller/laravel-breadcrumbs/issues/123>`_ - though Xdebug already handles this if installed

**Other Suggestions for Forks**

- The `documentation needs updating <https://github.com/davejamesmiller/laravel-breadcrumbs/pull/129#issuecomment-246171932>`_ to recommend using ``->name('name')`` instead of ``['as' => 'name']`` in routes (since Laravel 5.3)
- I was considering rewriting the documentation to make Route-bound breadcrumbs the standard instead of an after-thought, with a better explanation of how explicit and implicit binding work, since these seem to be things people get stuck on
- You will need to set up accounts on `Packagist <https://packagist.org/>`_ for package delivery and `Read the Docs <https://readthedocs.org/>`_ for documentation
- You might also want to set up `Travis CI <https://travis-ci.org/getting_started>`_ and `Coveralls <https://coveralls.io/>`_ - there are already config files for them in the repo

================================================================================
 Documentation
================================================================================

`View documentation >> <https://laravel-breadcrumbs.readthedocs.io/>`_

--------------------------------------------------------------------------------

Copyright © 2013-2015 Dave James Miller. Released under the `MIT License <https://laravel-breadcrumbs.readthedocs.io/en/latest/license.html>`_.
