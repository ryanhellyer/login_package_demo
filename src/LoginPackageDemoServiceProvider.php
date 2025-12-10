<?php

namespace RyanHellyer\LoginPackageDemo;

use Illuminate\Support\ServiceProvider;

class LoginPackageDemoServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/auth.php');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'login-package-demo');
    }
}
