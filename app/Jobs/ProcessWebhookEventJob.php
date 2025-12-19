<?php

namespace App\Jobs;

use App\Models\WebhookEvent;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Carbon;

class ProcessWebhookEventJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $webhookEvent;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The number of seconds to wait before retrying the job.
     *
     * @var array<int, int>
     */
    public $backoff = [1, 10, 100]; // 1 second, 10 seconds, 100 seconds

    /**
     * Create a new job instance.
     */
    public function __construct(WebhookEvent $webhookEvent)
    {
        $this->webhookEvent = $webhookEvent;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $endpoint = $this->webhookEvent->webhookEndpoint;

        // Ensure endpoint relationship is loaded if it's not already
        if (!$endpoint) {
             $this->webhookEvent->load('webhookEndpoint');
             $endpoint = $this->webhookEvent->webhookEndpoint;
        }


        if (!$endpoint || !$endpoint->active) {
            // If endpoint is not found or not active, mark webhook event as failed or just return
            $this->webhookEvent->update([
                'attempts' => $this->webhookEvent->attempts + 1,
                'response_status' => 0, // Indicate an error state
                // 'sent_at' should not be set if not sent
            ]);
            $this->fail(); // Mark job as failed, it won't be retried
            return;
        }

        try {
            $response = Http::timeout(5) // 5 second timeout for webhook calls
                            ->withHeaders([
                                'Content-Type' => 'application/json',
                                'X-Fdp-Signature' => $this->generateSignature($this->webhookEvent->payload, $endpoint->secret),
                            ])
                            ->post($endpoint->url, $this->webhookEvent->payload);

            $this->webhookEvent->update([
                'sent_at' => Carbon::now(),
                'response_status' => $response->status(),
                'attempts' => $this->webhookEvent->attempts + 1,
            ]);

            // If the webhook endpoint did not return a 2xx response, we should retry
            if (!$response->successful()) {
                // If it's the last attempt, fail, otherwise release for retry
                if ($this->attempts() >= $this->tries) {
                    $this->fail("Webhook call failed after " . $this->tries . " attempts. Status: " . $response->status());
                } else {
                    $this->release(now()->addSeconds($this->backoff[$this->attempts()]));
                }
            }
        } catch (\Exception $e) {
            $this->webhookEvent->update([
                'attempts' => $this->webhookEvent->attempts + 1,
                'response_status' => 0, // Indicate an error state
                // 'sent_at' should not be set if an exception occurred and it wasn't sent
            ]);
            // If it's the last attempt, mark as failed, otherwise release for retry
            if ($this->attempts() >= $this->tries) {
                $this->fail($e);
            } else {
                $this->release(now()->addSeconds($this->backoff[$this->attempts()]));
            }
        }
    }

    /**
     * Generate the HMAC-SHA256 signature for the webhook payload.
     *
     * @param array $payload
     * @param string $secret
     * @return string
     */
    protected function generateSignature(array $payload, string $secret): string
    {
        $payloadString = json_encode($payload);
        return 'sha256=' . hash_hmac('sha256', $payloadString, $secret);
    }
}