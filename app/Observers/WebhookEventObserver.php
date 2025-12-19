<?php

namespace App\Observers;

use App\Models\WebhookEvent;
use App\Jobs\ProcessWebhookEventJob; // Import the job

class WebhookEventObserver
{
    /**
     * Handle the WebhookEvent "created" event.
     */
    public function created(WebhookEvent $webhookEvent): void
    {
        ProcessWebhookEventJob::dispatch($webhookEvent);
    }

    /**
     * Handle the WebhookEvent "updated" event.
     */
    public function updated(WebhookEvent $webhookEvent): void
    {
        //
    }

    /**
     * Handle the WebhookEvent "deleted" event.
     */
    public function deleted(WebhookEvent $webhookEvent): void
    {
        //
    }

    /**
     * Handle the WebhookEvent "restored" event.
     */
    public function restored(WebhookEvent $webhookEvent): void
    {
        //
    }

    /**
     * Handle the WebhookEvent "force deleted" event.
     */
    public function forceDeleted(WebhookEvent $webhookEvent): void
    {
        //
    }
}