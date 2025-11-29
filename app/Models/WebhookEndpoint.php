<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebhookEndpoint extends Model
{
    use HasFactory;

    protected $fillable = [
        'merchant_id', 'url', 'secret', 'events', 'active'
    ];

    protected $casts = [
        'events' => 'array',
        'active' => 'boolean',
    ];

    public function merchant()
    {
        return $this->belongsTo(Merchant::class);
    }

    public function events()
    {
        return $this->hasMany(WebhookEvent::class);
    }
}
