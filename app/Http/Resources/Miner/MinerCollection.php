<?php

namespace App\Http\Resources\Miner;

use Illuminate\Http\Resources\Json\ResourceCollection;

class MinerCollection extends ResourceCollection
{
    public static $wrap = false;

    public $collects = MinerResource::class;
}
