<?php

namespace App\Models\Miner;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MinerPayout extends Model
{
    use HasFactory, SoftDeletes;

    public function getAmountAttribute(): float
    {
        return intval($this->attributes['amount']) / 100000000;
    }

    public function setAmountAttribute(float $value)
    {
        $this->attributes['amount'] = $value * 100000000;
    }
}
