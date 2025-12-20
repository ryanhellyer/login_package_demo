<?php

declare(strict_types=1);

namespace RyanHellyer\LoginPackageDemo\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'login-package-demo:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Interactive setup for Login Package Demo';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('🚀 Welcome to Login Package Demo Setup!');
        $this->newLine();

        // Check if config is published
        $configPath = config_path('login-package-demo-auth.php');
        $configPublished = File::exists($configPath);

        if (!$configPublished) {
            if ($this->confirm('Would you like to publish the configuration file?', true)) {
                $this->call('vendor:publish', [
                    '--provider' => 'RyanHellyer\LoginPackageDemo\LoginPackageDemoServiceProvider',
                    '--tag' => null,
                ]);
                $this->info('✓ Configuration file published!');
                $this->newLine();
            }
        }

        // Get user model
        $userModel = $this->ask('What is your User model class?', 'App\Models\User');
        
        // Ask about route customization
        $customizeRoutes = $this->confirm('Would you like to customize route paths?', false);
        
        $routePaths = [];
        if ($customizeRoutes) {
            $this->info('Enter custom paths (press Enter to use defaults):');
            $routePaths['login'] = $this->ask('Login path', 'login');
            $routePaths['register'] = $this->ask('Register path', 'register');
            $routePaths['logout'] = $this->ask('Logout path', 'logout');
            $routePaths['forgot_password'] = $this->ask('Forgot password path', 'forgot-password');
            $routePaths['reset_password'] = $this->ask('Reset password path', 'reset-password');
            $routePaths['verify_email'] = $this->ask('Verify email path', 'verify-email');
            $routePaths['confirm_password'] = $this->ask('Confirm password path', 'confirm-password');
            $routePaths['password'] = $this->ask('Password update path', 'password');
        }

        // Update .env file
        $this->updateEnvFile($userModel, $routePaths);

        // Update config file if published
        if ($configPublished || File::exists($configPath)) {
            $this->updateConfigFile($configPath, $userModel, $routePaths);
        }

        $this->newLine();
        $this->info('✅ Setup complete!');
        $this->newLine();
        $this->info('Next steps:');
        $this->line('1. Include forms in your views using: @include(\'login-package-demo::auth.login\')');
        $this->line('2. Visit your routes to test authentication');
        $this->line('3. Check README.md for customization options');

        return Command::SUCCESS;
    }

    /**
     * Update the .env file with new variables.
     */
    protected function updateEnvFile(string $userModel, array $routePaths): void
    {
        $envPath = base_path('.env');
        
        if (!File::exists($envPath)) {
            $this->warn('⚠ .env file not found. Skipping .env updates.');
            return;
        }

        $envContent = File::get($envPath);
        $updated = false;

        // Update user model
        if (preg_match('/^LOGIN_PACKAGE_USER_MODEL=.*$/m', $envContent)) {
            $envContent = preg_replace('/^LOGIN_PACKAGE_USER_MODEL=.*$/m', "LOGIN_PACKAGE_USER_MODEL={$userModel}", $envContent);
            $updated = true;
        } else {
            $envContent .= "\nLOGIN_PACKAGE_USER_MODEL={$userModel}\n";
            $updated = true;
        }

        // Update route paths
        foreach ($routePaths as $key => $value) {
            $envKey = 'LOGIN_PACKAGE_' . strtoupper(str_replace('_', '_', $key)) . '_PATH';
            
            // Convert snake_case to UPPER_SNAKE_CASE for env key
            $envKeyParts = explode('_', $key);
            $envKeyParts = array_map('strtoupper', $envKeyParts);
            $envKey = 'LOGIN_PACKAGE_' . implode('_', $envKeyParts) . '_PATH';
            
            if (preg_match("/^{$envKey}=.*$/m", $envContent)) {
                $envContent = preg_replace("/^{$envKey}=.*$/m", "{$envKey}={$value}", $envContent);
                $updated = true;
            } else {
                $envContent .= "{$envKey}={$value}\n";
                $updated = true;
            }
        }

        if ($updated) {
            File::put($envPath, $envContent);
            $this->info('✓ .env file updated!');
        }
    }

    /**
     * Update the config file with new values.
     */
    protected function updateConfigFile(string $configPath, string $userModel, array $routePaths): void
    {
        $configContent = File::get($configPath);
        $updated = false;

        // Update user model in config
        if (preg_match("/'user_model' => .*,/", $configContent)) {
            $configContent = preg_replace(
                "/'user_model' => .*,/",
                "'user_model' => env('LOGIN_PACKAGE_USER_MODEL', '{$userModel}'),",
                $configContent
            );
            $updated = true;
        }

        // Update route paths in config
        if (!empty($routePaths)) {
            foreach ($routePaths as $key => $value) {
                // Convert snake_case to UPPER_SNAKE_CASE for env key
                $envKeyParts = explode('_', $key);
                $envKeyParts = array_map('strtoupper', $envKeyParts);
                $envKey = 'LOGIN_PACKAGE_' . implode('_', $envKeyParts) . '_PATH';
                $configKey = $key;
                
                if (preg_match("/'{$configKey}' => .*,/", $configContent)) {
                    $configContent = preg_replace(
                        "/'{$configKey}' => .*,/",
                        "'{$configKey}' => env('{$envKey}', '{$value}'),",
                        $configContent
                    );
                    $updated = true;
                }
            }
        }

        if ($updated) {
            File::put($configPath, $configContent);
            $this->info('✓ Config file updated!');
        }
    }
}
