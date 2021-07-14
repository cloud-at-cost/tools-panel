<?php

namespace App\Http\Controllers;

use App\Http\Resources\Miner\MinerPayoutCollection;
use App\Models\Bitcoin\BitcoinMarketValue;
use App\Models\Miner;
use App\Models\Miner\MinerPayout;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Inertia\Inertia;

class PayoutsController extends Controller
{
    public function index(Request $request)
    {
        $payouts = MinerPayout::forUser($request->user())
            ->latest('created_at')
            ->paginate();

        return Inertia::render(
            'Payouts/Index',
            [
                'payouts' => new MinerPayoutCollection($payouts),
                'total' => (
                    MinerPayout::forUser($request->user())
                        ->deposits()
                        ->sum('amount')
                    - (MinerPayout::forUser($request->user())
                        ->withdrawals()
                        ->sum('amount') ?? 0)
                ) / 100000000,
                'conversion' => optional(BitcoinMarketValue::latest('created_at')->first())->price,
            ]
        );
    }

    public function create()
    {
        return Inertia::render(
            'Payouts/Create',
        );
    }

    public function store(Request $request)
    {
        $availableMiners = $request->user()->miners->keyBy('identifier');
        $minerIds = $request->user()->miners->keyBy('miner_id');

        $existing = 0;
        $new = 0;

        $payouts = collect($request->input('payouts'))
            ->transform(fn($payout) => (object)$payout);

        $validPayouts = $payouts->filter(fn($payout) => (
            property_exists($payout, 'packageID') && isset($availableMiners[$payout->packageID]))
            || isset($minerIds[$payout->minerID])
        );

        $validPayouts->each(function ($payout) use ($availableMiners, $minerIds, &$existing, &$new) {
            if(property_exists($payout, 'packageID') && isset($availableMiners[$payout->packageID])) {
                $miner = $availableMiners[$payout->packageID];
            }
            else {
                $miner = $minerIds[$payout->minerID];
            }

            $date = Carbon::parse($payout->date)->format('Y-m-d H:i');

            $actual = $miner->payouts()
                ->whereRaw(
                    'DATE_FORMAT(created_at, "%Y-%m-%d %H:%i") = ?',
                    $date
                )
                ->whereType($payout->type)
                ->firstOrNew();

            if ($actual->exists) {
                $existing++;
            } else {
                $new++;
            }

            $actual->created_at = $date;
            $actual->type = $payout->type;
            $actual->amount = $payout->amount;

            $actual->save();
        });

        if ($request->get('api')) {
            return [
                'total' => $payouts->count(),
                'successful' => $validPayouts->count(),
                'failed' => $payouts->count() - $validPayouts->count(),
                'new' => $new,
                'existing' => $existing
            ];
        }

        return Inertia::location(
            route('payouts.index')
        );
    }

    public function destroy(Request $request)
    {
        $user = $request->user();
        $total = 0;

        $user->miners->each(
            function(Miner $miner) use(&$total) {
                $total += $miner->payouts()->delete();
            }
        );

        return [
            'removed' => $total,
        ];
    }
}
