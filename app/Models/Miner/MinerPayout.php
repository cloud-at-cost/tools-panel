<?php

namespace App\Models\Miner;

use App\Models\Miner;
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
}
