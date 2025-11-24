<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
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
        // Force Carbon locale to French
        \Carbon\Carbon::setLocale('fr');

        // Enregistrer la gate pour la gestion des utilisateurs
        \Illuminate\Support\Facades\Gate::define('manage-users', function ($user) {
            return $user->isAdmin();
        });
    }
}
