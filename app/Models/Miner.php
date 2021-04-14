<?php

namespace App\Models;

use App\Models\Miner\MinerPayout;
use App\Models\Miner\MinerType;
use App\Traits\HasHash;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Miner extends Model
{
    use HasFactory, SoftDeletes, HasHash;

    protected $guarded = [];

    public function type(): BelongsTo
    {
        return $this->belongsTo(MinerType::class, 'miner_type_id');
    }

    public function payouts(): HasMany
    {
        return $this->hasMany(MinerPayout::class)
            ->latest('created_at');
    }

    public function getAmountPaidAttribute(): float
    {
        return intval($this->attributes['amount_paid']) / 100.0;
    }

    public function setAmountPaidAttribute(float $value)
    {
        $this->attributes['amount_paid'] = intval($value * 100);
    }

    public static function scopeForUser($query, User $user)
    {
        $query->whereUserId($user->id);
    }
}
