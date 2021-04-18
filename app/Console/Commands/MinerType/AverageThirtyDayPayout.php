<?php

namespace App\Console\Commands\MinerType;

use App\Enumerations\Miner\Classification;
use App\Models\Miner\MinerPayout;
use Illuminate\Console\Command;

class AverageThirtyDayPayout extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'miner-type:average:thirty-day';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculates the average 30 day payout by classification type';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $results = $this->calculateAveragePayout(Classification::ASIC);

        \App\Models\MinerType\AverageThirtyDayPayout::create([
            'classification' => Classification::ASIC,
            'bitcoins_per_month' => $results['bitcoin'] * 180,
            'total_sample_size' => $results['sample'],
        ]);

        $results = $this->calculateAveragePayout(Classification::GPU);

        \App\Models\MinerType\AverageThirtyDayPayout::create([
            'classification' => Classification::GPU,
            'bitcoins_per_month' => $results['bitcoin'] * 180,
            'total_sample_size' => $results['sample'],
        ]);

        return 0;
    }

    private function calculateAveragePayout(string $classification): array
    {
        $details = MinerPayout::whereHas('miner.type', fn($query) => $query->forClassification($classification))
            ->with('miner.type')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('miner_id')
            ->select('miner_id')
            ->selectRaw('SUM(amount) as amount')
            ->selectRaw('COUNT(1) as total')
            ->get()
            ->groupBy(fn($payout) => $payout->miner->type->slug);
        $data = $details->reduce(function($carry, $details) {
                return [
                    'bitcoin' => $carry['bitcoin'] + (
                        $details->sum('amount') / $details->sum('total') / $details->first()->miner->type->multiplier
                    ),
                    'sample' => $carry['sample'] + $details->sum('total'),
                ];
            }, [
                'bitcoin' => 0,
                'sample' => 0,
            ]);

        $data['bitcoin'] = $data['bitcoin'] / $details->count();

        return $data;
    }
}
