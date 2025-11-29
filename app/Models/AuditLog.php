<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AuditLog extends Model
{
    use HasFactory, SoftDeletes;
    
    public $timestamps = false;

    protected $fillable = [
        'user_type', 'user_id', 'merchant_id', 'event',
        'auditable_type', 'auditable_id', 'old_values',
        'new_values', 'ip', 'user_agent'
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];
}
