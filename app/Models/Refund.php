<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Refund extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'merchant_id', 'transaction_id', 'amount', 'currency', 'reason',
        'status', 'processed_at'
    ];

    protected $casts = [
        'processed_at' => 'datetime',
    ];

    public function merchant()
    {
        return $this->belongsTo(Merchant::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
