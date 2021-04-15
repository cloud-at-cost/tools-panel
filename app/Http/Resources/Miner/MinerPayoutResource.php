<?php

namespace App\Http\Resources\Miner;

use App\Models\Miner\MinerPayout;
use Illuminate\Http\Resources\Json\JsonResource;

class MinerPayoutResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $payout = $this->payout();

        return [
            'hash' => $payout->hash,
            'date' => $payout->created_at->format('Y-m-d H:i:s'),
            'amount' => $payout->amount,
            'type' => $payout->type,
            'miner' => new MinerResource($payout->miner)
        ];
    }

    private function payout(): MinerPayout
    {
        return $this->resource;
    }
}
