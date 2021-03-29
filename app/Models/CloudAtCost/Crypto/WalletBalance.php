<?php

namespace App\Models\CloudAtCost\Crypto;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletBalance extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getTotalReceivedAttribute(): float
    {
        return intval($this->attributes['total_received']) / 100000000;
    }

    public function setTotalReceivedAttribute(float $value)
    {
        $this->attributes['total_received'] = $value * 100000000;
    }

    public function getTotalSentAttribute(): float
    {
        return intval($this->attributes['total_sent']) / 100000000;
    }

    public function setTotalSentAttribute(float $value)
    {
        $this->attributes['total_sent'] = $value * 100000000;
    }

    public function getTotalFeesAttribute(): float
    {
        return intval($this->attributes['total_fees']) / 100000000;
    }

    public function setTotalFeesAttribute(float $value)
    {
        $this->attributes['total_fees'] = $value * 100000000;
    }

    public function getFinalBalanceAttribute(): float
    {
        return intval($this->attributes['final_balance']) / 100000000;
    }

    public function setFinalBalanceAttribute(float $value)
    {
        $this->attributes['final_balance'] = $value * 100000000;
    }
}
