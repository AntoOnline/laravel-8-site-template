# Laravel Skeleton
This Laravel skeleton aims to provide a site on top of which you can build your awesome Laravel projects. As a result, you will save days of development and get going quickly!
What do you get with this Laravel skeleton app:
-	User registration – Provide your name and email to receive a registration link.
-	User login – Provide your email and password to log in.
-	User logout – Logout securely.
-	Password change – Provide your old password to change to a new password.
-	Password reminder – Provide your email to get a password reminder.
-	User event log – See when you signed up, changed your password, and more.
-	Dark mode settings – Follow the dark side and change your theme.
-	Delete user account – Delete your account.
-	Email integration – Integrate effortlessly with your favorite email provider.
-	Google reCAPTCHA v3 – Integrate with Google reCAPTCHA and only allow humans.

![](https://anto.online/wp-content/uploads/2021/08/laravel-skeleton-project.gif)

## Prerequisites
This guide assumes you have:
- Composer installed. You can get composer from [here](https://getcomposer.org/).
- Git installed. You can get Git from [here](https://docs.github.com/en/get-started/quickstart/set-up-git).  
- A Working MySQL database.
- An SMTP email address.
- Google reCAPTCHA configured.

## Installation

Clone the code from our repo:
```
git clone https://github.com/RepositoriumCodice/laravel-8-site-template
cd laravel-8-site-template
composer install
```

## Configure Your App

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
- `CAPTCHA_SITEKEY`
- `CAPTCHA_SECRET`

### For CAPTCHA_SITEKEY and CAPTCHA_SECRET

This app uses google reCaptcha v3. Visit: https://www.google.com/recaptcha/about/ to get your keys.

Note: When loading your site on the localhost, use "localhost" and not "127.0.0.1" to avoid:
```
Localhost is not in the list of supported domains for this site key.
```

### For MAIL_* 

Provide your email server details for the site to send sign up emails.

### For DB_*

Provide your MySQL database details for the site to store user details.

## Final Laravel Setup

Run the Laravel migration tool:
```
php artisan migrate
```

Create an app key:
```
php artisan key:generate
```
## Running Your App

From your app root directory, run:

```
php artisan serve
```
Go to `localhost:8000` to view the app.

If you have set a different siteurl, go to that url.

Login credentials:  
Username: admin@example.com  
Password: admin
