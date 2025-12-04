<?php

namespace App\Models;

use App\Http\Enums\TransactionStatusEnum;
use App\Models\Customer;
use App\Models\Merchant;
use App\Observers\TransactionObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected static function boot(){
        parent::boot();

        Transaction::observe(TransactionObserver::class);
    }

    protected $fillable = [
        'merchant_id', 
        'customer_id', 
        'reference',
        'amount', 
        'currency',
        'provider', 
        'fees',
        'net_amount', 
        'status', 
        'description', 
        'callback_url', 
        'cancel_url',
        'paid_at', 
        'refunded_at',
        'custom_data',
        'payment_token',
        'payment_token_expires_at',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'refunded_at' => 'datetime',
        'status' => TransactionStatusEnum::class
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
