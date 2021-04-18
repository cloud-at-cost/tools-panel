<?php

namespace App\Http\Resources\Miner;

use App\Models\Bitcoin\BitcoinMarketValue;
use App\Models\Miner\MinerPayout;
use App\Models\Miner\MinerType;
use App\Models\MinerType\AverageThirtyDayPayout;
use Illuminate\Http\Resources\Json\JsonResource;

class MinerTypeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $type = $this->type();

        return [
            'hash' => $type->hash,
            'slug' => $type->slug,
            'name' => $type->name,
            'price' => $type->price,
            'classification' => $type->classification,
            'estimated_usd' => $this->calculateEstimatedUsd(),
        ];
    }

    private function type(): MinerType
    {
        return $this->resource;
    }

    private function calculateEstimatedUsd(): float
    {
        return AverageThirtyDayPayout::forMinerType($this->type())
            ->latest('created_at')
            ->first()
            ->bitcoins_per_month
            * $this->type()->multiplier
            * BitcoinMarketValue::latest('created_at')->first()->price;
    }
}
