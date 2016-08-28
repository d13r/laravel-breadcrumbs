# Release Checklist

- [ ] Ensure the documentation is up to date, particularly the changelog
- [ ] Merge the `develop` branch into the `master` branch (`git checkout master; git merge develop`)
- [ ] Push the code changes to GitHub (`git push`)
- [ ] Double-check the [Travis CI results](https://travis-ci.org/davejamesmiller/laravel-breadcrumbs)
- [ ] Tag the release (`git tag 1.2.3`)
- [ ] Push the tag (`git push --tag`)
- [ ] Check [Packagist](https://packagist.org/packages/davejamesmiller/laravel-breadcrumbs) is updated
- [ ] Notify anyone who opened [an issue or PR](https://github.com/davejamesmiller/laravel-breadcrumbs/issues?utf8=%E2%9C%93&q=) that has been fixed
