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
        $this->mergeConfigFrom(
            __DIR__.'/../config/auth.php', 'login-package-demo-auth'
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/auth.php');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'login-package-demo');

        $this->publishes([
            __DIR__.'/../config/auth.php' => config_path('login-package-demo-auth.php'),
        ]);
    }
}