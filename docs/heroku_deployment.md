# Heroku Deployment

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
