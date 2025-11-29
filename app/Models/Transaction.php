<?php

namespace App\Models;

use App\Models\Customer;
use App\Models\Merchant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'merchant_id', 'customer_id', 'amount', 'currency', 'fees',
        'net_amount', 'status', 'description', 'return_url', 'cancel_url',
        'paid_at', 'refunded_at'
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'refunded_at' => 'datetime',
    ];

    public function merchant()
    {
        return $this->belongsTo(Merchant::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function refunds()
    {
        return $this->hasMany(Refund::class);
    }

    public function webhookEvents()
    {
        return $this->hasMany(WebhookEvent::class);
    }
}
