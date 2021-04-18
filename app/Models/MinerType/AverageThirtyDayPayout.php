<?php

namespace App\Models\MinerType;

use App\Models\Miner\MinerType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AverageThirtyDayPayout extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getBitcoinsPerMonthAttribute(): float
    {
        return intval($this->attributes['bitcoins_per_month']) / 100000000;
    }

    public function setBitcoinsPerMonthAttribute(float $value)
    {
        $this->attributes['bitcoins_per_month'] = $value * 100000000;
    }

    public static function scopeForMinerType($query, MinerType $minerType)
    {
        $query->whereClassification($minerType->classification);
    }
}
