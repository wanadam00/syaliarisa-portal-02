<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
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

        // If APP_URL is an HTTPS URL (for example when using ngrok), force
        // generated URLs to use HTTPS. This is a safe fallback for local
        // development and prevents mixed-content issues when TLS is
        // terminated by a proxy (ngrok) and forwarded to the app.
        if (str_starts_with(config('app.url', ''), 'https://')) {
            URL::forceScheme('https');
        }
    }
}
