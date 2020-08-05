TextBoard
=========

![License](https://img.shields.io/github/license/linnit/textboard?style=for-the-badge)
![GitHub Workflow Status](https://img.shields.io/github/workflow/status/linnit/textboard/CI?style=for-the-badge)
![Code Climate maintainability](https://img.shields.io/codeclimate/maintainability/linnit/textboard?style=for-the-badge)

Requirements
------------
   * PHP 7.1+
   * PHP Extensions - php-imagick, php-pgsql
   * Postgres
   * [yarn][1]
   * and the [usual Symfony application requirements][2].

---

## Installation

### Heroku Deployment

```bash
# Replace 'textboard' with your Heroku app name
APPNAME=textboard

git clone https://github.com/linnit/textboard.git
cd textboard

heroku create $APPNAME --buildpack heroku/php
heroku buildpacks:add --index 1 heroku/nodejs

heroku git:remote -a $APPNAME
```

#### Add-ons

```bash
heroku addons:create heroku-postgresql:hobby-dev --as=DATABASE
heroku addons:create cloudamqp:lemur --as=ENQUEUE
heroku addons:create scheduler:standard
```

#### Environment Variables

```bash
heroku config:set APP_ENV=prod
heroku config:set APP_SECRET=$(php -r 'echo bin2hex(random_bytes(16));')

# Replace with your AWS S3 details
heroku config:set S3_ENDPOINT=""
heroku config:set S3_BUCKET=""
heroku config:set S3_REGION=""
heroku config:set S3_KEY=""
heroku config:set S3_SECRET=""
heroku config:set S3_ROOTURL=""
```

#### Deploy

```bash
git push heroku master
```


##### CloudFlare Proxy

If you're using CloudFlare to proxy requests, you'll need to set the `TRUSTED_PROXIES` variable, so we can securely get the user's IP address. And if you're using Heroku, we need to include the `10.0.0.0/8` range.

```bash
TRUSTED_PROXIES="10.0.0.0/8,"$(curl -s https://www.cloudflare.com/ips-v4 https://www.cloudflare.com/ips-v6 | tr '\n' ',' | sed 's/,$//')
heroku config:set TRUSTED_PROXIES=$TRUSTED_PROXIES
```

### Apache/PHP-FPM Deployment

```bash
git clone https://github.com/linnit/textboard.git
cd textboard
composer install
```

Configure environment variables

```bash
cp .env .env.local
chmod 600 .env.local
```

Edit .env.local and update database variables

Create database and table structure

```
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load
```

Images are processed in the background, to start the worker process, run the following

```bash
php bin/console enqueue:consume --setup-broker
```

#### Assets

```bash
yarn encore production
```

#### Cron

Cron is used to remove old posts and unban IP addresses

Obviously replace `<path-to-textboard>` with the full location on the repository

```
*/15 * * * *    <path-to-textboard>/bin/console app:delete-old-posts
*/15 * * * *    <path-to-textboard>/bin/console app:delete-old-bans
```

---

## File Uploads


Files can either be stored on the local filesystem or on a S3 compatible object storage bucket

### Local

In `config/packages/liip_imagine.yaml` change the filter caches to 'default'

### Apply image filters with a worker process

To ensure thumbnails and other filters are applied to images and a clean URL is served from the first request, you will need to set the environment variable `WAIT_IMAGE_FILTER` to true. You will then need to have the following worker process running at all times

```bash
php bin/console enqueue:consume --setup-broker
```

If you're using Heroku, this should be running as defined in the Procfile

## Tests

Run the following command to run tests:

```bash
./bin/phpunit
```

[1]: https://classic.yarnpkg.com/en/docs/install
[2]: https://symfony.com/doc/4.4/setup.html
