<?php

declare(strict_types = 1);

namespace App\Charts\Miners\Payouts;

use App\Models\Miner;
use App\Models\Miner\MinerPayout;
use App\Models\Miner\MinerType;
use Carbon\Carbon;
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
        $dates = MinerPayout::forUser($request->user())
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m-%d %H") as created')
            ->distinct()
            ->orderBy('created')
            ->get()
            ->transform(fn($row) => $row->created);

        $chart = Chartisan::build()
            ->labels($dates->toArray());

        $types = [];

        Miner::forUser($request->user())
            ->with('type')
            ->get()
            ->sortBy(fn(Miner $miner) => $miner->type->name)
            ->each(function(Miner $miner) use($chart, $dates, &$types) {
                $payouts = $miner->payouts()
                    ->deposits()
                    ->selectRaw('DATE_FORMAT(created_at, "%Y-%m-%d %H") as created')
                    ->selectRaw('amount AS amount')
                    ->get()
                    ->keyBy('created');

                if($payouts->count() === 0) {
                    return;
                }

                $data = [];

                foreach($dates as $key => $date) {
                    $data[$key] = optional($payouts[$date] ?? null)->amount;
                }

                $chart->dataset(
                    "{$miner->type->name}: {$miner->identifier}",
                    $data
                );

                if(!array_key_exists($miner->type->hash, $types)) {
                    $types[$miner->type->hash] = $miner->type;
                }
            });

        collect($types)
            ->each(function(MinerType $minerType) use($chart, $dates) {
                $data = [];
                $previous = null;

                $payouts = $minerType->priceHistory()
                    ->selectRaw('DATE_FORMAT(created_at, "%Y-%m-%d") as created')
                    ->selectRaw('AVG(bitcoins_per_month / 31.0 / 6.0 / 100000000) AS amount')
                    ->groupByRaw('DATE_FORMAT(created_at, "%Y-%m-%d")')
                    ->get()
                    ->keyBy('created');

                foreach($dates as $key => $date) {
                    $converted = Carbon::createFromFormat("Y-m-d H", $date)->toDateString();
                    $previous = $data[$key] = (optional($payouts[$converted] ?? null)->amount) ?? $previous;
                }

                $chart->dataset(
                    "{$minerType->name}: Bitcoin",
                    $data
                );
            });

        return $chart;
    }
}
