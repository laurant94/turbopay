<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'merchant_id',
        'email',
        'firstname',
        'lastname',
        'phone',
    ];

    protected $appends = [
        'quick_details',
        'fullname'
    ];

    public function getFullnameAttribute(){
        return $this->firstname . ' '. $this->lastname;
    }

    public function getQuickDetailsAttribute(){
        return $this->firstname . ' '. $this->lastname . " ($this->email)";
    }

    public function merchant()
    {
        return $this->belongsTo(Merchant::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
