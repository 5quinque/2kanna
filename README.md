TextBoard
=========

Requirements
------------
   * PHP 7.1+
   * PHP Extensions - php-imagick, php-pgsql
   * Postgres
   * and the [usual Symfony application requirements][1].

Heroku Deployment
-----------------

```bash
heroku create textboard
```

### Add-ons

```bash
heroku addons:create heroku-postgresql:hobby-dev --as=DATABASE
heroku addons:create cloudamqp:lemur --as=ENQUEUE
heroku addons:create scheduler:standard
```

Apache/PHP-FPM Deployment
-------------------------

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

### Cron

Cron is used to remove old posts and unban IP addresses

Obviously replace `<path-to-textboard>` with the full location on the repository

```
*/15 * * * *    <path-to-textboard>/bin/console app:delete-old-posts
*/15 * * * *    <path-to-textboard>/bin/console app:delete-old-bans
```


File Uploads
------------

Files can either be stored on the local filesystem or on a S3 compatible object storage bucket

In `config/packages/liip_imagine.yaml` change the filter caches to 'default'

[1]: https://symfony.com/doc/4.4/setup.html
