<?php

namespace App\Http\Resources\Miner;

use App\Models\Miner;
use Illuminate\Http\Resources\Json\JsonResource;

class MinerResource extends JsonResource
{
    public static $wrap = false;

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $miner = $this->miner();

        return [
            'hash' => $miner->hash,
            'miner_id' => $miner->miner_id,
            'identifier' => $miner->identifier,
            'amount_paid' => $miner->amount_paid,
            'type' => new MinerTypeResource($miner->type),
            'purchase_date' => optional($miner->purchase_date)->toDateString(),
            'estimated_activation_date' => optional($miner->estimated_activation_date)->toDateString(),
            'activation_date' => optional($miner->activation_date)->toDateString(),
        ];
    }

    public function miner(): Miner
    {
        return $this->resource;
    }
}
