<?php

namespace App\Models\MinerType;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MinerTypePriceHistory extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function getPriceAttribute(): float
    {
        return intval($this->attributes['price']) / 100.0;
    }

    public function setPriceAttribute(float $value)
    {
        $this->attributes['price'] = intval($value * 100);
    }

    public function getBitcoinsPerMonthAttribute(): float
    {
        return intval($this->attributes['bitcoins_per_month']) / 100000000;
    }

    public function setBitcoinsPerMonthAttribute(float $value)
    {
        $this->attributes['bitcoins_per_month'] = $value * 100000000;
    }
}
