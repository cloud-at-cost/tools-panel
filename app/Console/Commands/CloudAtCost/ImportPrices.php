<?php

namespace App\Console\Commands\CloudAtCost;

use App\Models\Miner\MinerType;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

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
        $this->process(
            $this->retrieveHtml('https://cloudatcost.com/virtual-gpu-miners')
        );

        $this->process(
            $this->retrieveHtml('https://cloudatcost.com/virtual-asic-miners')
        );

        return 0;
    }

    private function process(string $html)
    {
        $minerTypes = [];
        preg_match_all('/[a-zA-Z]{1,2}\d+[a]? Miner/', $html, $minerTypes);

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
                        'created_at' => today(),
                    ])->first();

                if(!$history) {
                    $minerType->priceHistory()->create([
                        'price' => $priceList[$index],
                        'bitcoins_per_month' => $bitcoinsPerMonth[$index],
                        'created_at' => today(),
                    ]);
                }

            });
    }

    private function retrieveHtml(string $url):string
    {
        $client = new Client([
            'verify' => false,
            'decode_content' => false
        ]);

        for($x = 0; $x < 10; $x++) {
            try {
                $content = $client->get($url)
                    ->getBody();
                return $content->getContents();
            }
            catch(\Exception $exception) {
                if($x === 9) {
                    throw $exception;
                }
            }
        }
    }
}
