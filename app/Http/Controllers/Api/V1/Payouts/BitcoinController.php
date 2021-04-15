<?php

namespace App\Http\Controllers\Api\V1\Payouts;

use App\Http\Controllers\Controller;
use App\Models\Bitcoin\BitcoinMarketValue;
use App\Models\Miner\MinerPayout;
use Illuminate\Http\Request;

class BitcoinController extends Controller
{
    public function get(Request $request)
    {
        if (!$request->user()) {
            return 0;
        }

        $bitcoin = BitcoinMarketValue::latest('created_at')->first();

        return $bitcoin->price * (MinerPayout::forUser($request->user())->sum('amount') / 100000000);
    }
}
