<?php

use App\Jobs\ProviderAuthTokenJob;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


Schedule::job(new ProviderAuthTokenJob)
->cron('0,05 * * * *')   // every hour at minute 0 and 40
->withoutOverlapping()   // avoid duplicates if previous run still active
->onOneServer();    