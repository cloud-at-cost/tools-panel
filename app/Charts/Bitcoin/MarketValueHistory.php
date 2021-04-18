<?php

declare(strict_types = 1);

namespace App\Charts\Bitcoin;

use App\Models\Bitcoin\BitcoinMarketValue;
use Chartisan\PHP\Chartisan;
use ConsoleTVs\Charts\BaseChart;
use Illuminate\Http\Request;

class MarketValueHistory extends BaseChart
{
    /**
     * Handles the HTTP request for the given chart.
     * It must always return an instance of Chartisan
     * and never a string or an array.
     */
    public function handler(Request $request): Chartisan
    {
        $marketValue = BitcoinMarketValue::latest('created_at')
            ->get();

        return Chartisan::build()
            ->labels($marketValue
                ->map(
                    fn(BitcoinMarketValue $marketValue) => $marketValue->created_at->toDateTimeString()
                )->toArray()
            )
            ->dataset('Bitcoin Price',
                $marketValue->map(fn(BitcoinMarketValue $marketValue) => $marketValue->price)->toArray()
            );
    }
}
