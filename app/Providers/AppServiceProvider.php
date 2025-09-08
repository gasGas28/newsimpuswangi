<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\MaintenanceModeManager;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        /**
         * Shim/compat untuk package lama yang masih resolve
         * Illuminate\Contracts\Foundation\MaintenanceMode (sudah dihapus di L10+).
         * Kita arahkan ke MaintenanceModeManager milik Laravel 12.
         */
        $this->app->bind('Illuminate\Contracts\Foundation\MaintenanceMode', function ($app) {
            return $app->make(MaintenanceModeManager::class);
        });

        // Tambahan alias agar resolve via alias lama juga nyantol
        $this->app->alias(MaintenanceModeManager::class, 'Illuminate\Contracts\Foundation\MaintenanceMode');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
