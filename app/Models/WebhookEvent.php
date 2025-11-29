<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WebhookEvent extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'webhook_endpoint_id', 'merchant_id', 'transaction_id',
        'event', 'payload', 'sent_at', 'response_status', 'attempts'
    ];

    protected $casts = [
        'payload' => 'array',
        'sent_at' => 'datetime',
    ];

    public function webhookEndpoint()
    {
        return $this->belongsTo(WebhookEndpoint::class);
    }

    public function merchant()
    {
        return $this->belongsTo(Merchant::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
