<?php

namespace App\Http\Resources\Miner;

use App\Models\Miner\MinerPayout;
use Illuminate\Http\Resources\Json\JsonResource;

class MinerPayoutResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $payout = $this->payout();

        return [
            'date' => $payout->created_at,
            'amount' => $payout->amount,
            'type' => $payout->type,
        ];
    }

    private function payout(): MinerPayout
    {
        return $this->resource;
    }
}
