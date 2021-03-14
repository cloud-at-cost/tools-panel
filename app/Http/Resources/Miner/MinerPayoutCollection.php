<?php

namespace App\Http\Resources\Miner;

use Illuminate\Http\Resources\Json\ResourceCollection;

class MinerPayoutCollection extends ResourceCollection
{
    public $collects = MinerPayoutResource::class;

    public static $wrap = false;
}
