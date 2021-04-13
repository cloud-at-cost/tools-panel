<?php

use App\Http\Controllers\Api\V1\CloudAtCost\PlatformOperatingSystemController;
use App\Http\Controllers\Api\V1\Payouts\BitcoinController;
use App\Http\Controllers\MinerController;
use App\Http\Controllers\PayoutsController;
use App\Http\Resources\Miner\MinerTypeCollection;
use App\Models\Miner;
use App\Models\Miner\MinerPayout;
use App\Models\Miner\MinerType;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'stats' => [
            'miners' => Miner::whereHas('payouts')->count(),
            'payouts' => MinerPayout::whereHas('miner')->count(),
        ],
        'types' => new MinerTypeCollection(
            MinerType::orderBy('name')
                ->get()
                ->keyBy('slug')
            ),
    ]);
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->name('dashboard');

Route::get('api/v1/platform/{platform}/operating-systems', [PlatformOperatingSystemController::class, 'index']);

Route::group([
    'middleware' => ['auth:sanctum', 'verified']
], function() {
    Route::resource('miners', MinerController::class);
    Route::get('api/v1/payouts/bitcoin', [BitcoinController::class, 'get']);

    Route::group([
        'prefix' => 'payouts'
    ], function() {
        Route::get('', [PayoutsController::class, 'index'])
            ->name('payouts.index');
        Route::get('create', [PayoutsController::class, 'create'])
            ->name('payouts.create');
        Route::post('create', [PayoutsController::class, 'store'])
            ->name('payouts.store');
    });
});
