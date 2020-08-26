<p align="center">
    <img width="150px" src="public/vi.png">
</p>

2Kanna
======

![GitHub](https://img.shields.io/github/license/linnit/2kanna?style=flat-square)
![GitHub Workflow Status](https://img.shields.io/github/workflow/status/linnit/2kanna/Symfony%20with%20PostgreSQL?style=flat-square)
![Codecov](https://img.shields.io/codecov/c/github/linnit/2kanna?style=flat-square)

Requirements
------------
   * PHP 7.1+
   * PHP Extensions - php-imagick, php-pgsql
   * Postgres
   * [yarn][1]
   * and the [usual Symfony application requirements][2].

---

## Features

   * Image & Video WebM file upload (Local storage or S3 compatible)
   * Tree post structure
   * Code formatting
   * Crosslinking posts and posts from other boards
   * Autoupdating threads
   * Allow anonymous board creation
   * Sticky posts

---

## Installation

   * [Heroku Deployment](docs/heroku_deployment.md)
   * [Apache/PHP-FPM Deployment](docs/apache_php_deployment.md)

---

## File Uploads

Files can either be stored on the local filesystem or on a S3 compatible object storage bucket

### Local File Storage

In `config/packages/vich_uploader.yaml` change 'upload_destination' to 'local_filesystem'  
In `config/packages/liip_imagine.yaml` change the filter caches to 'default'

### S3 File Stoage

In `config/packages/vich_uploader.yaml` ensure 'upload_destination' is set to 's3_filesystem'  
In `config/packages/liip_imagine.yaml` ensure the filter caches are set to 's3_cache'


### Apply image filters using a worker process

To ensure thumbnails and other filters are applied to images and a clean URL is served from the first request, you will need to set the environment variable `WAIT_IMAGE_FILTER` to true. You will then need to have the following worker process running at all times

```bash
php bin/console enqueue:consume --setup-broker
```

If you're using Heroku, this should be running as defined in the Procfile

---

## Tests

Run the following command to run tests:

```bash
./bin/phpunit
```

With coverage:

```bash
./bin/phpunit --coverage-html build/coverage-report
```

[1]: https://classic.yarnpkg.com/en/docs/install
[2]: https://symfony.com/doc/4.4/setup.html
