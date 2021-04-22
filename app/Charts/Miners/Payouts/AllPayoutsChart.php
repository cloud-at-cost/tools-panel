<?php

declare(strict_types=1);

namespace App\Charts\Miners\Payouts;

use App\Models\Miner;
use App\Models\Miner\MinerPayout;
use App\Models\Miner\MinerType;
use Carbon\Carbon;
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
        $startDate = Carbon::parse($request->get('startDate'));
        $endDate = Carbon::parse($request->get('endDate'));

        $dates = MinerPayout::selectRaw('DATE_FORMAT(created_at, "%Y-%m-%d %H") as created')
            ->distinct()
            ->where('created_at', '>=', $startDate)
            ->where('created_at', '<=', $endDate)
            ->orderBy('created')
            ->get()
            ->transform(fn($row) => $row->created);

        $chart = Chartisan::build()
            ->labels($dates->toArray());

        $minerTypes = MinerType::orderBy('name')
            ->get()
            ->filter(function (MinerType $minerType) use ($request) {
                if (empty($request->get('class'))) {
                    return true;
                }

                return $minerType->slug[0] === $request->get('class');
            });

        $minerTypes->each(function (MinerType $minerType) use ($chart, $dates) {
            $data = [];
            $previous = null;

            $payouts = $minerType->priceHistory()
                ->selectRaw('DATE_FORMAT(created_at, "%Y-%m-%d") as created')
                ->selectRaw('AVG(bitcoins_per_month / 31.0 / 6.0 / 100000000) AS amount')
                ->groupByRaw('DATE_FORMAT(created_at, "%Y-%m-%d")')
                ->get()
                ->keyBy('created');

            foreach ($dates as $key => $date) {
                $converted = Carbon::createFromFormat("Y-m-d H", $date)->toDateString();
                $previous = $data[$key] = (optional($payouts[$converted] ?? null)->amount) ?? $previous;
            }

            $chart->dataset(
                "{$minerType->name}: Bitcoin",
                $data
            );
        });

        $minerTypes->each(function (MinerType $minerType) use ($dates, $chart, $request, $startDate, $endDate) {
            if (Miner::whereMinerTypeId($minerType->id)->count() < 2) {
                return;
            }

            $payouts = MinerPayout::forType($minerType)
                ->deposits()
                ->where('created_at', '>=', $startDate)
                ->where('created_at', '<=', $endDate)
                ->selectRaw('DATE_FORMAT(created_at, "%Y-%m-%d %H") as created')
                ->selectRaw('AVG(amount) AS amount')
                ->groupBy('created')
                ->get()
                ->keyBy('created');

            if ($payouts->count() === 0) {
                return;
            }

            $data = [];

            foreach ($dates as $key => $date) {
                $data[$key] = optional($payouts[$date] ?? null)->amount;
            }

            $chart->dataset($minerType->name, $data);
        });

        return $chart;
    }
}
