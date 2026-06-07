<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
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
            Gate::define('ceo-only', fn($user) => $user->role === 'ceo');
    Gate::define('staff', fn($user) => in_array($user->role, ['ceo', 'admin']));
    }
}
