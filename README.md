TextBoard
=========

Requirements
------------
   * PHP 7.1+
   * PHP Extensions mysqlnd, gd
   * Mariadb-server 5.5+
   * and the [usual Symfony application requirements][1].

Installation
------------

```bash
git clone https://github.com/linnit/textboard.git
cd textboard
composer install
```

Create mysql user and grant privileges

```sql
CREATE USER 'textboard'@'localhost' IDENTIFIED BY 'somerandompassword';
GRANT ALL PRIVILEGES ON textboard.* TO 'textboard'@'localhost';
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

Cron
----

Cron is used to remove old posts and unban IP addresses

Obviously replace `<path-to-textboard>` with the full location on the repository

```
*/15 * * * *    <path-to-textboard>/bin/console app:delete-old-posts
*/15 * * * *    <path-to-textboard>/bin/console app:delete-old-bans
```

[1]: https://symfony.com/doc/4.4/setup.html
