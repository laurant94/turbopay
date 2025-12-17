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
}