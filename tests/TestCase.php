<?php

namespace Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
use RyanHellyer\LoginPackageDemo\LoginPackageDemoServiceProvider;

abstract class TestCase extends BaseTestCase
{
    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return array<int, class-string<\Illuminate\Support\ServiceProvider>>
     */
    protected function getPackageProviders($app)
    {
        return [
            LoginPackageDemoServiceProvider::class,
        ];
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function defineEnvironment($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);

        // Set default user model for testing
        $app['config']->set('login-package-demo-auth.user_model', \Tests\Support\User::class);
    }

    /**
     * Define database migrations.
     *
     * @return void
     */
    protected function defineDatabaseMigrations()
    {
        // Load Laravel's default migrations if available, otherwise use our test migrations
        if (method_exists($this, 'loadLaravelMigrations')) {
            $this->loadLaravelMigrations();
        }
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}
