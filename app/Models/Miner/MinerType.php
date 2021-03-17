<?php

namespace App\Models\Miner;

use App\Models\MinerType\MinerTypePriceHistory;
use App\Traits\HasHash;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class MinerType extends Model
{
    use HasFactory, SoftDeletes, HasHash;

    protected $guarded = [];

    /**
     * @return HasMany
     */
    public function priceHistory(): HasMany
    {
        return $this->hasMany(MinerTypePriceHistory::class);
    }
}
