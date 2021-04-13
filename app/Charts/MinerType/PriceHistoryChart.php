<?php

declare(strict_types = 1);

namespace App\Charts\MinerType;

use App\Models\Miner\MinerType;
use App\Models\MinerType\MinerTypePriceHistory;
use Chartisan\PHP\Chartisan;
use ConsoleTVs\Charts\BaseChart;
use Illuminate\Http\Request;

class PriceHistoryChart extends BaseChart
{
    /**
     * Handles the HTTP request for the given chart.
     * It must always return an instance of Chartisan
     * and never a string or an array.
     */
    public function handler(Request $request): Chartisan
    {
        $dates = MinerTypePriceHistory::selectRaw('DATE_FORMAT(created_at, "%Y-%m-%d %H") as created')
            ->distinct()
            ->where('created_at', '>=', now()->subDays(30))
            ->orderBy('created')
            ->get()
            ->transform(fn($row) => $row->created);

        $chart = Chartisan::build()
            ->labels($dates->toArray());

        MinerType::get()
            ->filter(function(MinerType $minerType) use($request) {
                if(empty($request->get('class'))) {
                    return true;
                }

                return $minerType->slug[0] === $request->get('class');
            })
            ->each(function(MinerType $minerType) use($dates, $chart) {
                $prices = $minerType->priceHistory()
                    ->where('created_at', '>=', now()->subDays(30))
                    ->selectRaw('AVG(price) as price')
                    ->selectRaw('AVG(bitcoins_per_month) as bitcoins_per_month')
                    ->selectRaw('DATE_FORMAT(created_at, "%Y-%m-%d %H") as created')
                    ->groupBy('created')
                    ->get()
                    ->keyBy('created');

                $data = [];

                foreach($dates as $key => $date) {
                    $data[$key] = optional($prices[$date] ?? null)->price;
                }

                $chart->dataset($minerType->name, $data);
            });

        return $chart;
    }
}
