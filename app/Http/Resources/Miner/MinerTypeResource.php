<?php

namespace App\Http\Resources\Miner;

use App\Models\Miner\MinerType;
use Illuminate\Http\Resources\Json\JsonResource;

class MinerTypeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
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
        ];
    }

    private function type(): MinerType
    {
        return $this->resource;
    }
}
