<?php

namespace App\Console\Commands\Bitcoin;

use App\Models\Bitcoin\BitcoinMarketValue;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class UpdateMarketValue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bitcoin:update-market-value';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Retrieves an updated market value of Bitcoin';

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
        $response = Http::withHeaders([
                'X-CMC_PRO_API_KEY' => config('bitcoin.api_key'),
            ])->get('https://pro-api.coinmarketcap.com/v1/cryptocurrency/listings/latest?start=1&limit=5000&convert=USD');

        $bitcoin = (object)collect($response->json('data'))
            ->first(fn($details) => $details['symbol'] == 'BTC');

        $model = BitcoinMarketValue::firstOrNew([
            'created_at' => $bitcoin->quote['USD']['last_updated'],
        ]);

        $model->price = $bitcoin->quote['USD']['price'];
        $model->save();

        return 0;
    }
}
