<?php

namespace App\Http\Resources\Miner;

use Illuminate\Http\Resources\Json\ResourceCollection;

class MinerTypeCollection extends ResourceCollection
{
    public static $wrap = false;
    public $collects = MinerTypeResource::class;
}
