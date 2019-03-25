# ob.test

olybet test task

### Requirements

PHP 7.1+

### How to get up & running

```
git clone https://github.com/ob2019/ob.test
cd ob.test
composer install
```

Copy .env.example to .env. Inside .env file set-up your database credentials:

```
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=
```
Migrate database
```
php artisan migrate:fresh
```

## Running the tests

```
"vendor/bin/phpunit" --testdox
```
