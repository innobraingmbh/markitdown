# Upgrade Guide

# v2.X.X to v3.0.0

## Breaking Changes

### Upgrade script moved to PHP

- remove `./vendor/innobrain/markitdown/setup-python-env.sh` from your `post-autoload-dump` scripts in `composer.json`
- run the installation command again:
  ```bash
  php artisan markitdown:install
  ```
