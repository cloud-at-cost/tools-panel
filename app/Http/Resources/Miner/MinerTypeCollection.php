<?php

namespace App\Http\Resources\Miner;

use Illuminate\Http\Resources\Json\ResourceCollection;

class MinerTypeCollection extends ResourceCollection
{
    public $collects = MinerTypeResource::class;
    public static $wrap = false;
}
