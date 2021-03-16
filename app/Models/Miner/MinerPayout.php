<?php

namespace App\Models\Miner;

use App\Models\Miner;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class MinerPayout extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function miner(): BelongsTo
    {
        return $this->belongsTo(Miner::class);
    }

    public function getAmountAttribute(): float
    {
        return intval($this->attributes['amount']) / 100000000;
    }

    public function setAmountAttribute(float $value)
    {
        $this->attributes['amount'] = $value * 100000000;
    }

    public static function scopeForUser($query, User $user)
    {
        $query->whereHas('miner', fn($q) => $q->whereUserId($user->id));
    }

    public static function scopeDeposits($query)
    {
        $query->whereType('deposit');
    }

    public static function scopeForType($query, MinerType $minerType)
    {
        $query->whereHas('miner', fn($q) => $q->whereMinerTypeId($minerType->id));
    }
}
