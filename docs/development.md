# Development

## CSS / JS

Using webpack encore to manage CSS and Javascript

https://symfony.com/doc/current/frontend.html


### Hot Module Reloading

Auto refresh your browser when updating CSS/JS

```bash
yarn dev-server
```

## Testing

Create a local mysql docker container

```bash
docker create \
  --name mysql57 \
  -p 3306:3306 \
  --health-cmd="mysqladmin ping" --health-interval=10s --health-timeout=5s --health-retries=3 \
  -e "MYSQL_ALLOW_EMPTY_PASSWORD=false" \
  -e "MYSQL_DATABASE=symfony" \
  -e "MYSQL_ROOT_PASSWORD=symfony" \
  mysql:5.7
```

Patch the CI file

```bash
patch -p0 < .github/workflows/local_ci.patch
```

Run the workflow

```bash
act -P ubuntu-latest=shivammathur/node:latest -j symfony-local
```

When you're satisfied, revert the changes

```bash
patch -p0 -R < .github/workflows/local_ci.patch
```