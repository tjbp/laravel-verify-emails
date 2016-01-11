# Laravel Verify Emails

[![StyleCI](https://styleci.io/repos/48846764/shield?style=flat)](https://styleci.io/repos/48846764)
[![Build Status](https://travis-ci.org/tjbp/laravel-verify-emails.svg)](https://travis-ci.org/tjbp/laravel-verify-emails)
[![Total Downloads](https://poser.pugx.org/tjbp/laravel-verify-emails/d/total.svg)](https://packagist.org/packages/tjbp/laravel-verify-emails)
[![Latest Stable Version](https://poser.pugx.org/tjbp/laravel-verify-emails/v/stable.svg)](https://packagist.org/packages/tjbp/laravel-verify-emails)
[![Latest Unstable Version](https://poser.pugx.org/tjbp/laravel-verify-emails/v/unstable.svg)](https://packagist.org/packages/tjbp/laravel-verify-emails)
[![License](https://poser.pugx.org/tjbp/laravel-verify-emails/license.svg)](https://packagist.org/packages/tjbp/laravel-verify-emails)

This package allows you to verify account emails in Laravel using the same pattern as password resets.

## Installation

This package is installable [with Composer via Packagist](https://packagist.org/packages/tjbp/laravel-verify-emails).

## Configuration

Add the following to `config/auth.php`:

```php
'verify_emails' => [
    'users' => [
        'provider' => 'users',
        'email' => 'emails.verify',
        'table' => 'email_tokens',
        'expire' => 60,
    ],
],
```

Change the password.table setting to `email_tokens` too, allowing password reset and email verification tokens to use the same table. Alternatively, create a new table for the email verification tokens using the same definitions as your password resets table and configure the above accordingly.

## Usage

Implement the `LaravelVerifyEmails\Contracts\Auth\CanVerifyEmail` contract in your `App\User` model and use the `LaravelVerifyEmails\Auth\VerifyEmails\CanVerifyEmail` trait to include the necessary methods. By default, a boolean column on your users table named `verified` is expected. This behaviour can be altered by overriding the methods in the trait.

Run `php artisan make:verify-emails` to generate views and a controller, then add `Route::controller('email', 'App\Http\Controllers\Auth\EmailController')` to your routes. Alternatively, use the `LaravelVerifyEmails\Foundation\Auth\VerifiesEmails` trait in a controller of your choice.

Finally, call `$user->unverify()` to mark the user as unverified and send a verification token to their email address. To catch unverified users, replace the `auth` route middleware in `App\Http\Kernel` with `LaravelVerifyEmails\Auth\Middleware\AuthenticateAndVerifyEmail`. If you'd rather use your own middleware, or want to check if a user is verified elsewhere, call `$user->isVerified()`.

## Licence

Laravel Verify Email is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
