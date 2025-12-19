<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Enums\EventType; // Import the Enum

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'merchant_id',
        'user_id',
        'event_type',
        'payload',
    ];

    protected $casts = [
        'event_type' => EventType::class, // Cast to the EventType Enum
        'payload' => 'array',
    ];

    // Define relationships
    public function merchant()
    {
        return $this->belongsTo(Merchant::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::created(function (Event $event) {
            // Find all active WebhookEndpoints for the same merchant
            $webhookEndpoints = WebhookEndpoint::where('merchant_id', $event->merchant_id)
                ->where('active', true)
                ->get();

            foreach ($webhookEndpoints as $endpoint) {
                // Check if the endpoint is subscribed to this event_type OR to all events (if 'events' is empty)
                if (empty($endpoint->events) || in_array($event->event_type->value, $endpoint->events)) {
                    // Create a WebhookEvent to be processed later
                    WebhookEvent::create([
                        'webhook_endpoint_id' => $endpoint->id,
                        'merchant_id' => $event->merchant_id,
                        'transaction_id' => $event->payload['id'] ?? null, // Assuming transaction_id might be in payload
                        'event' => $event->event_type->value,
                        'payload' => $event->payload,
                        // 'sent_at', 'response_status', 'attempts' will be handled by the job
                    ]);
                }
            }
        });
    }
}
