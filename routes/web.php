<?php

use App\Http\Controllers\MinerController;
use App\Http\Controllers\PayoutsController;
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
            'miners' => \App\Models\Miner::count(),
            'payouts' => \App\Models\Miner\MinerPayout::count(),
        ],
    ]);
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->name('dashboard');

Route::group([
    'middleware' => ['auth:sanctum', 'verified']
], function() {
    Route::resource('miners', MinerController::class);

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
