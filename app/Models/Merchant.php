<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;

class Merchant extends Model
{

    use HasFactory, SoftDeletes, HasUuids;
    use HasApiTokens;

    protected $fillable = [
        'user_id', 'name', 'registration_number', 'tax_id', 'address', 'country',
        'status', 'activated_at'
    ];

    protected $casts = [
        'activated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function apiKeys()
    {
        return $this->hasMany(ApiKey::class);
    }

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }

    public function bankAccounts()
    {
        return $this->hasMany(BankAccount::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function refunds()
    {
        return $this->hasMany(Refund::class);
    }

    public function payouts()
    {
        return $this->hasMany(Payout::class);
    }

    public function webhookEndpoints()
    {
        return $this->hasMany(WebhookEndpoint::class);
    }

    public function webhookEvents()
    {
        return $this->hasMany(WebhookEvent::class);
    }

    public function fees()
    {
        return $this->hasMany(Fee::class);
    }
}
