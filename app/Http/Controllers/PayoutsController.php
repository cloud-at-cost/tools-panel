<?php

namespace App\Http\Controllers;

use App\Http\Resources\Miner\MinerPayoutCollection;
use App\Models\Miner;
use App\Models\Miner\MinerPayout;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Inertia\Inertia;

class PayoutsController extends Controller
{
    public function index(Request $request)
    {
        $payouts = MinerPayout::whereHas('miner', fn($query) => $query->whereUserId($request->user()->id))
            ->latest('created_at')
            ->paginate();

        return Inertia::render(
            'Payouts/Index',
            [
                'payouts' => new MinerPayoutCollection($payouts)
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

        collect($request->input('payouts'))
            ->transform(fn($payout) => (object)$payout)
            ->filter(fn($payout) => isset($availableMiners[$payout->packageID]))
            ->each(function($payout) use($availableMiners) {
                $miner = $availableMiners[$payout->packageID];

                $actual = $miner->payouts()
                    ->whereCreatedAt($payout->date)
                    ->whereType($payout->type)
                    ->firstOrNew();

                $actual->created_at = $payout->date;
                $actual->type = $payout->type;
                $actual->amount = $payout->amount;

                $actual->save();
            });

        return Inertia::location(
            route('payouts.index')
        );
    }
}
