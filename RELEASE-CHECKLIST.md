# Release Checklist

- [ ] Ensure the [README](README.md) is up to date, particularly the [changelog](README.md#changelog)
- [ ] Merge the changes into the `master` branch (if applicable)
- [ ] Make sure [all tests are passing](https://travis-ci.org/davejamesmiller/laravel-breadcrumbs)
- [ ] Push the code changes to GitHub (`git push`)
- [ ] Tag the release (`git tag 1.2.3`)
- [ ] Push the tag (`git push --tag`)

## New versions of Laravel

- [ ] Update [`composer.json`](composer.json)
    - [ ] `illuminate/*`
    - [ ] `laravel/framework`
    - [ ] [`orchestra/testbench`](https://github.com/orchestral/testbench#version-compatibility)
- [ ] Update [`.travis.yml`](.travis.yml)
    - [ ] Laravel versions
    - [ ] PHP versions
- [ ] Update [`README`](README.md)
    - [ ] [`Compatibility Chart`](README.md#compatibility-chart)
    - [ ] [`Changelog`](README.md#changelog)
- [ ] Update the [test app](https://github.com/davejamesmiller/laravel-breadcrumbs-test)
- [ ] Commit, tag and push (as above)
