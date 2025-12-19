<?php

namespace App\Jobs;

use App\Services\Payments\Authenticator\MtnAuthenticator;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class ProviderAuthTokenJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $token = MtnAuthenticator::auth();

        Log::alert("token", $token);
        // $this->command->info("Clé publique (à copier maintenant) : {$result['key']}");

    }

}
