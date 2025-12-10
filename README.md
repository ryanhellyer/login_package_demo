# Laravel Login Package Demo

This is a demo package that extracts the authentication controllers from a Laravel application.

## Installation

1.  Add the package to your `composer.json` file:

    ```json
    "require": {
        "ryanhellyer/login-package-demo": "@dev"
    },
    "repositories": [
        {
            "type": "path",
            "url": "../login_package_demo"
        }
    ]
    ```

2.  Run `composer update`.

3.  Add the service provider to your `config/app.php` file:

    ```php
    'providers' => [
        // ...
        RyanHellyer\LoginPackageDemo\LoginPackageDemoServiceProvider::class,
    ],
    ```

4.  Publish the configuration file:

    ```bash
    php artisan vendor:publish --provider="RyanHellyer\LoginPackageDemo\LoginPackageDemoServiceProvider"
    ```

5.  Update your `config/login-package-demo-auth.php` file to specify the user model you want to use.

## Usage

This package provides the following routes:

-   `login`
-   `logout`
-   `register`
-   `password/reset`
-   `password/email`
-   `password/confirm`
-   `verify-email`

You can use these routes in your application as you would with the default Laravel authentication system.
