<?php

declare(strict_types = 1);

namespace App\Charts\Miners\Payouts;

use App\Models\Miner\MinerPayout;
use Chartisan\PHP\Chartisan;
use ConsoleTVs\Charts\BaseChart;
use Illuminate\Http\Request;

class MyPayoutsChart extends BaseChart
{
    /**
     * Handles the HTTP request for the given chart.
     * It must always return an instance of ChartisaMinerTypeSeedern
     * and never a string or an array.
     */
    public function handler(Request $request): Chartisan
    {
        $myPayouts = MinerPayout::forUser($request->user())
            ->deposits()
            ->orderBy('created_at', 'asc')
            ->get();

        return Chartisan::build()
            ->labels(
                $myPayouts->map(
                    fn(MinerPayout $minerPayout)
                        => $minerPayout->created_at->toDateString()
                )->toArray()
            )
            ->dataset(
                'Bitcoin Earned',
                $myPayouts->map(
                    fn(MinerPayout $minerPayout) => $minerPayout->amount
                )->toArray()
            );
    }
}
