<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\ContactInfo;
use Inertia\Inertia;

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
        Inertia::share([
            'contactInfo' => fn() => ContactInfo::first(), // assuming single record
        ]);
    }
}
