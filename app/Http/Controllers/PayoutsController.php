<?php

namespace App\Http\Controllers;

use App\Http\Resources\Miner\MinerPayoutCollection;
use App\Models\Bitcoin\BitcoinMarketValue;
use App\Models\Miner\MinerPayout;
use Illuminate\Http\Request;
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
                'total' => MinerPayout::forUser($request->user())
                        ->deposits()
                        ->sum('amount') / 100000000,
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
        $minerIds = $request->user()->miners->keyBy('identifier');

        $existing = 0;
        $new = 0;

        $payouts = collect($request->input('payouts'))
            ->transform(fn($payout) => (object)$payout);

        $validPayouts = $payouts->filter(fn($payout) => isset($availableMiners[$payout->packageID]) || isset($minerIds[$payout->minerID]));

        $validPayouts->each(function ($payout) use ($availableMiners, $minerIds, &$existing, &$new) {
            if(isset($availableMiners[$payout->packageID])) {
                $miner = $availableMiners[$payout->packageID];
            }
            else {
                $miner = $minerIds[$payout->minerID];
            }

            $actual = $miner->payouts()
                ->whereCreatedAt($payout->date)
                ->whereType($payout->type)
                ->firstOrNew();

            if ($actual->exists) {
                $existing++;
            } else {
                $new++;
            }

            $actual->created_at = $payout->date;
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
}
