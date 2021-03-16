<?php

declare(strict_types = 1);

namespace App\Charts\Miners\Payouts;

use App\Models\Miner\MinerPayout;
use App\Models\Miner\MinerType;
use Chartisan\PHP\Chartisan;
use ConsoleTVs\Charts\BaseChart;
use Illuminate\Http\Request;

class AllPayoutsChart extends BaseChart
{
    /**
     * Handles the HTTP request for the given chart.
     * It must always return an instance of Chartisan
     * and never a string or an array.
     */
    public function handler(Request $request): Chartisan
    {
        $dates = MinerPayout::selectRaw('DATE_FORMAT(created_at, "%Y-%c-%d %H") as created')
            ->distinct()
            ->get()
            ->transform(fn($row) => $row->created);

        $chart = Chartisan::build()
            ->labels($dates->toArray());

        MinerType::get()
            ->each(function(MinerType $minerType) use($dates, $chart) {
                $payouts = MinerPayout::forType($minerType)
                    ->deposits()
                    ->selectRaw('DATE_FORMAT(created_at, "%Y-%c-%d %H") as created')
                    ->selectRaw('AVG(amount) AS amount')
                    ->groupBy('created')
                    ->get()
                    ->keyBy('created');

                if($payouts->count() === 0) {
                    return;
                }

                $data = [];

                foreach($dates as $key => $date) {
                    $data[$key] = optional($payouts[$date])->amount;
                }

                $chart->dataset($minerType->name, $data);
            });

        return $chart;
    }
}
