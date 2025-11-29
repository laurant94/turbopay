<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fee extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'merchant_id', 'currency', 'min_amount', 'max_amount',
        'percent', 'fixed', 'scope'
    ];

    public function merchant()
    {
        return $this->belongsTo(Merchant::class);
    }
}
