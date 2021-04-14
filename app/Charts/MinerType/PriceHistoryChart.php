<?php

declare(strict_types = 1);

namespace App\Charts\MinerType;

use App\Models\Miner\MinerType;
use App\Models\MinerType\MinerTypePriceHistory;
use Carbon\Carbon;
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
        $startDate = Carbon::parse($request->get('startDate'));
        $endDate = Carbon::parse($request->get('endDate'));

        $dates = MinerTypePriceHistory::selectRaw('DATE_FORMAT(created_at, "%Y-%m-%d %H") as created')
            ->distinct()
            ->where('created_at', '>=', $startDate)
            ->where('created_at', '<=', $endDate)
            ->orderBy('created')
            ->get()
            ->transform(fn($row) => $row->created);

        $chart = Chartisan::build()
            ->labels($dates->toArray());

        MinerType::orderBy('name')
            ->get()
            ->filter(function(MinerType $minerType) use($request) {
                if(empty($request->get('class'))) {
                    return true;
                }

                return $minerType->slug[0] === $request->get('class');
            })
            ->each(function(MinerType $minerType) use($dates, $chart, $request, $startDate, $endDate) {
                $prices = $minerType->priceHistory()
                    ->where('created_at', '>=', $startDate)
                    ->where('created_at', '<=', $endDate)
                    ->selectRaw('AVG(price) as price')
                    ->selectRaw('AVG(bitcoins_per_month) as bitcoins_per_month')
                    ->selectRaw('DATE_FORMAT(created_at, "%Y-%m-%d %H") as created')
                    ->groupBy('created')
                    ->get()
                    ->keyBy('created');

                $data = [];

                $previous = null;
                foreach($dates as $key => $date) {
                    $previous = $data[$key] = optional($prices[$date] ?? null)->price ?? $previous;
                }

                $chart->dataset($minerType->name, $data);
            });

        return $chart;
    }
}
