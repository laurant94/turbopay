<?php

namespace App\Models;

use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ApiKey extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'merchant_id',
        'name',
        'key_prefix',
        'key_hash',
        'type',
        'scopes',
        'last_used_at',
        'revoked_at',
        'active',
        'note',
    ];

    protected $hidden = ['key_hash'];

    protected $casts = [
        'scopes' => 'array',
        'last_used_at' => 'datetime',
        'revoked_at' => 'datetime',
        'active' => 'boolean',
    ];


    public function checkSecret(string $secret): bool
    {
        return Hash::check($secret, $this->key_hash);
    }

    public function markUsed(): void
    {
        $this->update(['last_used_at' => now()]);
    }

    public function revoke(?string $reason = null): void
    {
        $this->update([
            'revoked_at' => now(),
            'active' => false,
            'note' => $reason
        ]);
    }

    public function isActive(): bool
    {
        return $this->active && is_null($this->revoked_at);
    }

    public function hasScope(string $scope): bool
    {
        $scopes = $this->scopes ?? [];
        // wildcard support: payment.* etc.
        foreach ($scopes as $s) {
            if ($s === $scope) return true;
            if (str_ends_with($s, '*')) {
                $base = rtrim($s, '*');
                if (str_starts_with($scope, $base)) return true;
            }
        }
        return false;
    }


    

    public function merchant()
    {
        return $this->belongsTo(Merchant::class);
    }
}
