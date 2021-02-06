# Go2TR Test Backend Project

## Installation
```
# clone from git
git clone git@github.com:meysam001/GO2TR-test.git

# install packages
cd GO2TR-test
composer install

# create .env file
copy .env.example .env

# generate key
php artisan key:generate

# create a database and set its config to .env file

# generate tables
php artisan migrate

# insert data
php artisan db:seed

```

## API Documentation (Swagger)
Run following endpoint, `api/documentation` e.g: `http://localhost/GO2TR/public/api/documentation` to see api documentation.
or import the file, `public/docs/api-docs.json` into a swagger online editor like: `https://editor.swagger.io/`

## Unit Test
Run following command to run tests:
```
vendor/bin/phpunit ./tests/Feature
```

