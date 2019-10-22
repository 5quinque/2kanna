TextBoard
=========

Requirements
------------
   * PHP 7.3+
   * PHP Extensions mysqlnd
   * Mariadb-server 5.5+
   * and the [usual Symfony application requirements][1].

Installation
------------

```bash
git clone https://github.com/linnit/textboard.git
cd textboard
composer install
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
*/15 * * * *    <path-to-textboard>/bin/console app:unban-ips
```

[1]: https://symfony.com/doc/4.4/setup.html
