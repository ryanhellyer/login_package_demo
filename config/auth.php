<?php

return [
    /*
    |--------------------------------------------------------------------------
    | User Model
    |--------------------------------------------------------------------------
    |
    | The user model class that should be used for authentication.
    |
    */
    'user_model' => env('LOGIN_PACKAGE_USER_MODEL', App\Models\User::class),

    /*
    |--------------------------------------------------------------------------
    | Route Paths
    |--------------------------------------------------------------------------
    |
    | Customize the URL paths for authentication routes. These can be
    | overridden via environment variables or by publishing this config file.
    |
    */
    'routes' => [
        'login' => env('LOGIN_PACKAGE_LOGIN_PATH', 'login'),
        'register' => env('LOGIN_PACKAGE_REGISTER_PATH', 'register'),
        'logout' => env('LOGIN_PACKAGE_LOGOUT_PATH', 'logout'),
        'forgot_password' => env('LOGIN_PACKAGE_FORGOT_PASSWORD_PATH', 'forgot-password'),
        'reset_password' => env('LOGIN_PACKAGE_RESET_PASSWORD_PATH', 'reset-password'),
        'verify_email' => env('LOGIN_PACKAGE_VERIFY_EMAIL_PATH', 'verify-email'),
        'confirm_password' => env('LOGIN_PACKAGE_CONFIRM_PASSWORD_PATH', 'confirm-password'),
        'password' => env('LOGIN_PACKAGE_PASSWORD_PATH', 'password'),
    ],
];
