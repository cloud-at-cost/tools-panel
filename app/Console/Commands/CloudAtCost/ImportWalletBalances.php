<?php

namespace App\Console\Commands\CloudAtCost;

use App\Models\CloudAtCost\Crypto\WalletBalance;
use Http;
use Illuminate\Console\Command;

class ImportWalletBalances extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cloud-at-cost:import-wallets';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports the wallet balances for CloudAtCost';

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
        $this->downloadWallet('bc1q0h27g4ntw2le35f3kthvry9ln6g4dl7fcqn4dd', 'BTC');
        $this->downloadWallet('0xb6100e5b8BC5f441EE9D3589D0681790367de7e6', 'ETH');

        return 0;
    }

    private function downloadWallet(string $wallet, string $unit)
    {
        $body = Http::get(
            "https://www.blockchain.com/" . strtolower($unit) . "/address/$wallet"
        )->body();

        $transactions = [];
        preg_match(
            '/<div[^>]*><div[^>]*><div><span[^>]*>(Number of )?Transactions<\/span><\/div><\/div><div[^>]*><span[^>]*>(\d+)/i',
            $body,
            $transactions
        );

        $transactions = $transactions[2] ?? $transactions[1];

        $finalBalance = [];
        preg_match(
            '/<div[^>]*><div[^>]*><div><span[^>]*>Final Balance<\/span><\/div><\/div><div[^>]*><span[^>]*>(\d+\.\d+)/i',
            $body,
            $finalBalance
        );

        $finalBalance = $finalBalance[1];

        $totalSent = [];
        preg_match(
            '/<div[^>]*><div[^>]*><div><span[^>]*>Total Sent<\/span><\/div><\/div><div[^>]*><span[^>]*>(\d+\.\d+)/i',
            $body,
            $totalSent
        );

        $totalSent = $totalSent[1];

        $totalReceived = [];
        preg_match(
            '/<div[^>]*><div[^>]*><div><span[^>]*>Total Received<\/span><\/div><\/div><div[^>]*><span[^>]*>(\d+\.\d+)/i',
            $body,
            $totalReceived
        );

        $totalReceived = $totalReceived[1];

        $totalFees = [];
        preg_match(
            '/<div[^>]*><div[^>]*><div><span[^>]*>Total Fees<\/span><\/div><\/div><div[^>]*><span[^>]*>(\d+\.\d+)/i',
            $body,
            $totalFees
        );

        $totalFees = collect($totalFees)->skip(1)->first();

        WalletBalance::create([
            'wallet' => $wallet,
            'unit' => $unit,
            'transactions' => $transactions ?? 0,
            'total_received' => $totalReceived ?? 0,
            'total_sent' => $totalSent ?? 0,
            'total_fees' => $totalFees ?? 0,
            'final_balance' => $finalBalance ?? 0,
        ]);
    }
}
