<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\WebhookEvent; // Import the model
use App\Observers\WebhookEventObserver; // Import the observer
use App\Models\Event; // Import the Event model
use App\Observers\EventObserver; // Import the EventObserver

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
        WebhookEvent::observe(WebhookEventObserver::class);
    }
}