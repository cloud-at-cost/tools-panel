<?php

namespace App\Console\Commands\CloudAtCost;

use App\Models\Miner;
use App\Models\Miner\MinerType;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use KubAT\PhpSimple\HtmlDomParser;

class ImportPrices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cloud-at-cost:import-prices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports the prices from the Cloud At Cost page';

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
        $client = new Client([
            'verify' => false,
            'decode_content' => false
        ]);

        $content = $client->get('https://cloudatcost.com/virtual-miners')
            ->getBody();
        $html = $content->getContents();

        $minerTypes = [];
        preg_match_all('/[a-zA-Z]{1,2}\d+ Miner/', $html, $minerTypes);

        $bitcoinsPerMonth = [];
        preg_match_all('/(\d\.\d+) <strong[^>]*>BTC\/Month/i', $html, $bitcoinsPerMonth);
        $bitcoinsPerMonth = $bitcoinsPerMonth[1];

        $priceList = [];
        preg_match_all('/\$(\d+)\/One-time/i', $html, $priceList);
        $priceList = $priceList[1];

        collect($minerTypes[0])
            ->unique()
            ->values()
            ->each(function($type, $index) use($priceList, $bitcoinsPerMonth) {
                $minerType = MinerType::where([
                    'slug' => Str::slug($type),
                    'name' => $type,
                ])->first();

                if(!$minerType) {
                    $minerType = MinerType::create([
                        'slug' => Str::slug($type),
                        'name' => $type,
                    ]);
                }

                $history = $minerType->priceHistory()
                    ->where([
                        'price' => $priceList[$index] * 100,
                        'bitcoins_per_month' => $bitcoinsPerMonth[$index] * 100000000,
                    ])->first();

                if(!$history) {
                    $minerType->priceHistory()->create([
                        'price' => $priceList[$index],
                        'bitcoins_per_month' => $bitcoinsPerMonth[$index],
                    ]);
                }

            });

        return 0;
    }
}
