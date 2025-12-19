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

4.  Run the interactive setup command (recommended):

    ```bash
    php artisan login-package-demo:install
    ```

    This will:
    - Prompt you for your User model class
    - Optionally customize route paths
    - Automatically add `.env` variables
    - Publish and configure the config file

    **Or manually configure:**

    ```bash
    php artisan vendor:publish --provider="RyanHellyer\LoginPackageDemo\LoginPackageDemoServiceProvider"
    ```

    Then update your `config/login-package-demo-auth.php` file and `.env` file manually.

## Usage

This package provides the following routes:

-   `login`
-   `logout`
-   `register`
-   `forgot-password`
-   `reset-password`
-   `verify-email`
-   `confirm-password`
-   `password`

### Using Built-in Views

You can include the package's built-in form views anywhere in your application:

```blade
{{-- Include login form --}}
@include('login-package-demo::auth.login')

{{-- Include registration form --}}
@include('login-package-demo::auth.register')

{{-- Include forgot password form --}}
@include('login-package-demo::auth.forgot-password')

{{-- Include confirm password form --}}
@include('login-package-demo::auth.confirm-password')
```

The GET routes redirect to `/` by default, so users should embed forms using the `@include()` directive rather than visiting dedicated pages.

## Customizing Routes

All route paths can be customized to match your application's URL structure. You can override routes in two ways:

### Method 1: Environment Variables

Add the following to your `.env` file:

```env
LOGIN_PACKAGE_LOGIN_PATH=sign-in
LOGIN_PACKAGE_REGISTER_PATH=sign-up
LOGIN_PACKAGE_LOGOUT_PATH=sign-out
LOGIN_PACKAGE_FORGOT_PASSWORD_PATH=forgot-password
LOGIN_PACKAGE_RESET_PASSWORD_PATH=reset-password
LOGIN_PACKAGE_VERIFY_EMAIL_PATH=verify-email
LOGIN_PACKAGE_CONFIRM_PASSWORD_PATH=confirm-password
LOGIN_PACKAGE_PASSWORD_PATH=password
```

### Method 2: Config File

After publishing the config file, edit `config/login-package-demo-auth.php`:

```php
'routes' => [
    'login' => 'sign-in',
    'register' => 'sign-up',
    'logout' => 'sign-out',
    // ... etc
],
```

### Important Notes

- Route names remain consistent regardless of path customization. For example, `route('login')` will always work, even if you change the path to `sign-in`.
- If you customize `reset-password` or `verify-email` paths, the route parameters (`{token}`, `{id}/{hash}`) will be automatically appended to your custom path.
- Default paths are used if no customization is provided, so the package works out of the box.
