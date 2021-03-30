# Apache/PHP-FPM Deployment

```bash
git clone https://github.com/linnit/2kanna.git
cd 2kanna
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

Replace `<path-to-2kanna>` with the full location on the repository

```
*/15 * * * *    <path-to-2kanna>/bin/console app:delete-old-posts
*/15 * * * *    <path-to-2kanna>/bin/console app:delete-old-bans
```

---