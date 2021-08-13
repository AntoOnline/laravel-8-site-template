# Laravel Basic Auth App
The purpose of this app is to provide a basic auth site on top of which you can build your awesome Laravel projects.

## Prerequisites
This guide assumes you have composer and git set up. You can get composer from [here](https://getcomposer.org/), and a guide to setting up git [here](https://docs.github.com/en/get-started/quickstart/set-up-git).  
This guide also assumes you have a working database made for this app.
## Installation
```
git clone https://github.com/RepositoriumCodice/laravel-8-site-template
cd laravel-8-site-template
composer install
```
## Setup
Copy and rename the file `.env.example` to `.env`.
Next, open your `.env` file and set the following variables:
- `APP_NAME`
- `DB_CONNECTION` (if different than mysql)
- `DB_HOST` (if other than localhost)
- `DB_PORT` (if other than 3306)
- `DB_DATABASE`
- `DB_USERNAME`
- `DB_PASSWORD`
- `MAIL_MAILER`
- `MAIL_HOST`
- `MAIL_PORT`
- `MAIL_USERNAME`
- `MAIL_PASSWORD`
- `MAIL_ENCRYPTION`
- `MAIL_FROM_ADDRESS`

Run the migrations:
```
php artisan migrate
```

Create an app key:
```
php artisan key:generate
```

## Captcha
This app uses google reCaptcha. The variables `CAPTCHA_SITEKEY` and `CAPTCHA_SECRET` are developer keys for testing only. For production version, use your own secure keys.

## Running
From your app root directory, run:
```
php artisan serve
```
Go to `127.0.0.1:8000` to view the app.
If you have set a different siteurl, go to that url.

Login credentials:  
Username: admin@example.com  
Password: admin
