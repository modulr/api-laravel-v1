# modulr-api-laravel


## Table of Contents

- [Install](#install)
- [Routes](#routes)
  - [Authentication](authentication)
  - [Password request](password-request)


### Install

1. Clone repository
```
$ git clone https://github.com/modulr/modulr-api-laravel.git
```

2. Enter folder
```
$ cd modulr-api-laravel
```

3. Install composer dependencies
```
~/modulr-api-laravel$ composer install
```

4. Generate APP_KEY
```
~/modulr-api-laravel$ php artisan key:generate
```

5. Configure .env file
```
// Add database params
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=homestead
DB_USERNAME=homestead
DB_PASSWORD=secret
```

6. Run migrations
```
~/modulr-api-laravel$ php artisan migrate
```

7. Run seeders * *optional* *
> NOTE: Seeds create 10 users fake with gravatar

```
~/modulr-api-laravel$ php artisan db:seed
```


### Routes

Authentication

- POST /auth/login
- POST /auth/logout
- POST /auth/register
- GET /auth/user

Password reset

- POST /password/create
- GET /password/find/{token}
- POST /password/reset
