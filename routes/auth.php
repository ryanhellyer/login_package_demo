<?php

declare(strict_types=1);

use RyanHellyer\LoginPackageDemo\AuthenticatedSessionController;
use RyanHellyer\LoginPackageDemo\ConfirmablePasswordController;
use RyanHellyer\LoginPackageDemo\EmailVerificationNotificationController;
use RyanHellyer\LoginPackageDemo\EmailVerificationPromptController;
use RyanHellyer\LoginPackageDemo\NewPasswordController;
use RyanHellyer\LoginPackageDemo\PasswordController;
use RyanHellyer\LoginPackageDemo\PasswordResetLinkController;
use RyanHellyer\LoginPackageDemo\RegisteredUserController;
use RyanHellyer\LoginPackageDemo\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function (): void {
    $routes = config('login-package-demo-auth.routes', []);
    
    Route::middleware('guest')->group(function () use ($routes): void {
        Route::get($routes['register'] ?? 'register', fn(): \Illuminate\Http\RedirectResponse => redirect('/'))->name('register');

        Route::post($routes['register'] ?? 'register', [RegisteredUserController::class, 'store']);

        Route::get($routes['login'] ?? 'login', fn(): \Illuminate\Http\RedirectResponse => redirect('/'))->name('login');

        Route::post($routes['login'] ?? 'login', [AuthenticatedSessionController::class, 'store']);

        Route::get($routes['forgot_password'] ?? 'forgot-password', fn(): \Illuminate\Http\RedirectResponse => redirect('/'))->name('password.request');

        Route::post($routes['forgot_password'] ?? 'forgot-password', [PasswordResetLinkController::class, 'store'])
            ->name('password.email');

        $resetPasswordPath = $routes['reset_password'] ?? 'reset-password';
        
        Route::get($resetPasswordPath . '/{token}', [NewPasswordController::class, 'create'])
            ->name('password.reset');

        Route::post($resetPasswordPath, [NewPasswordController::class, 'store'])
            ->name('password.store');
    });

    Route::middleware('auth')->group(function () use ($routes): void {
        $verifyEmailPath = $routes['verify_email'] ?? 'verify-email';
        
        Route::get($verifyEmailPath, EmailVerificationPromptController::class)
            ->name('verification.notice');

        Route::get($verifyEmailPath . '/{id}/{hash}', VerifyEmailController::class)
            ->middleware(['signed', 'throttle:6,1'])
            ->name('verification.verify');

        Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
            ->middleware('throttle:6,1')
            ->name('verification.send');

        Route::get($routes['confirm_password'] ?? 'confirm-password', fn(): \Illuminate\Http\RedirectResponse => redirect('/'))->name('password.confirm');

        Route::post($routes['confirm_password'] ?? 'confirm-password', [ConfirmablePasswordController::class, 'store']);

        Route::put($routes['password'] ?? 'password', [PasswordController::class, 'update'])->name('password.update');

        $logoutPath = $routes['logout'] ?? 'logout';
        
        Route::post($logoutPath, [AuthenticatedSessionController::class, 'destroy'])
            ->name('logout');
        
        Route::get($logoutPath, [AuthenticatedSessionController::class, 'destroy'])
            ->name('logout.get');
    });
});