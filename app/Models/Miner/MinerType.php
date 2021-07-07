<?php

namespace App\Models\Miner;

use App\Enumerations\Miner\Classification;
use App\Models\MinerType\MinerTypePriceHistory;
use App\Traits\HasHash;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class MinerType extends Model
{
    use HasFactory, SoftDeletes, HasHash;

    private static MinerType $asic;
    private static MinerType $gpu;

    protected $guarded = [];

    protected $appends = [
        'multiplier',
        'current_bitcoins_per_month'
    ];

    /**
     * @return HasMany
     */
    public function priceHistory(): HasMany
    {
        return $this->hasMany(MinerTypePriceHistory::class);
    }

    public function getMultiplierAttribute(): int
    {
        $baseModel = static::findBaseModel($this);

        $currentRate = $this->current_bitcoins_per_month;
        $baseRate = $baseModel->current_bitcoins_per_month;

        return intval($currentRate / $baseRate);
    }

    public function getCurrentBitcoinsPerMonthAttribute(): float
    {
        return optional($this->priceHistory
            ->sortByDesc('created_at')
            ->values()
            ->first())
            ->bitcoins_per_month ?? 0;
    }

    public function getIsBaseModelAttribute(): bool
    {
        return in_array($this->slug, ['m1-miner', 'm1a-miner']);
    }

    public function getClassificationAttribute(): string
    {
        if(Str::contains($this->slug,'a-')) {
            return Classification::ASIC;
        }

        return Classification::GPU;
    }

    private static function findBaseModel(MinerType $minerType): MinerType
    {
        if($minerType->classification === Classification::ASIC && isset(static::$asic)) {
            return static::$asic;
        }
        elseif($minerType->classification === Classification::GPU && isset(static::$gpu)) {
            return static::$gpu;
        }

        if($minerType->is_base_model && $minerType->classification === Classification::GPU) {
            static::$gpu = $minerType;
            return static::$gpu;
        }

        if($minerType->is_base_model && $minerType->classification === Classification::ASIC) {
            static::$asic = $minerType;
            return static::$asic;
        }

        if($minerType->classification === Classification::GPU) {
            static::$gpu = MinerType::whereSlug('m1-miner')->with('priceHistory')->first();
            return static::$gpu;
        }

        static::$asic = MinerType::whereSlug('m1a-miner')->with('priceHistory')->first();
        return static::$asic;
    }

    public static function scopeForClassification($query, string $classification)
    {
        switch ($classification) {
            case Classification::GPU:
                $query->where('slug', 'not like', '%a-%');
                break;
            case Classification::ASIC:
                $query->where('slug', 'like', '%a-%');
                break;
        }
    }
}
