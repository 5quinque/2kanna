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
```

[1]: https://symfony.com/doc/4.4/setup.html
