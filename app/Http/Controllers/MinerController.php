<?php

namespace App\Http\Controllers;

use App\Http\Requests\Miner\CreateRequest;
use App\Http\Requests\Miner\UpdateRequest;
use App\Http\Resources\Miner\MinerCollection;
use App\Http\Resources\Miner\MinerPayoutCollection;
use App\Http\Resources\Miner\MinerResource;
use App\Http\Resources\Miner\MinerTypeCollection;
use App\Models\Miner;
use App\Models\Miner\MinerType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Inertia\Inertia;

class MinerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        return Inertia::render(
            'Miners/Index', [
                'miners' => new MinerCollection(request()->user()->miners->load('type')),
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Inertia\Response
     */
    public function create()
    {
        return Inertia::render(
            'Miners/Create',
            [
                'types' => new MinerTypeCollection(MinerType::get()),
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRequest $request)
    {
        $miner = Miner::create([
            'miner_type_id' => $request->type()->id,
            'miner_id' => $request->input('miner_id'),
            'identifier' => $request->input('identifier'),
            'amount_paid' => $request->input('amount_paid'),
            'user_id' => $request->user()->id,
        ]);

        return Inertia::location(
            route('miners.show', $miner)
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  Miner $miner
     * @return \Inertia\Response
     */
    public function show(Miner $miner)
    {
        return Inertia::render(
            'Miners/Show',
            [
                'miner' => new MinerResource($miner),
                'recentPayouts' => new MinerPayoutCollection($miner->payouts()->limit(10)->get()),
            ]
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Miner $miner
     * @return \Inertia\Response
     */
    public function edit(Miner $miner)
    {
        return Inertia::render(
            'Miners/Edit',
            [
                'miner' => new MinerResource($miner->load('type')),
                'types' => new MinerTypeCollection(MinerType::get()),
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Miner $miner
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Miner $miner)
    {
        collect($request->validated())
            ->each(function($value, $key) use($request, $miner) {
                if($key === 'type') {
                    $miner->miner_type_id = $request->type()->id;
                }
                else {
                    $miner->$key = $value;
                }
            });

        $miner->save();

        return Inertia::location(
            route('miners.show', $miner)
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Miner $miner
     * @return \Illuminate\Http\Response
     */
    public function destroy(Miner $miner)
    {
        $miner->delete();

        return Inertia::location(
            route('miners.index')
        );
    }
}
