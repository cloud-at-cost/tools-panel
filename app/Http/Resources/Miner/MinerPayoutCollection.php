<?php

namespace App\Http\Resources\Miner;

use Illuminate\Http\Resources\Json\ResourceCollection;

class MinerPayoutCollection extends ResourceCollection
{
    public static $wrap = false;
    public $collects = MinerPayoutResource::class;
}
