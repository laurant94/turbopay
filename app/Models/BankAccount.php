<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BankAccount extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        'merchant_id', 'holder_name', 'type', 'iban_or_number', 'swift_or_operator',
        'country', 'status', 'verified_at'
    ];

    protected $casts = [
        'verified_at' => 'datetime',
        'iban_or_number' => 'encrypted',
        'swift_or_operator' => 'encrypted',
    ];

    public function merchant()
    {
        return $this->belongsTo(Merchant::class);
    }

    public function payouts()
    {
        return $this->hasMany(Payout::class);
    }
}
