<?php

use App\Http\Controllers\Api\V1\CloudAtCost\VirtualMachineController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'prefix' => 'cloud-at-cost',
    'middleware' => 'auth:sanctum',
], function() {
    Route::group([
        'prefix' => 'virtual-machine',
    ], function() {
        Route::post('', [VirtualMachineController::class, 'index'])
            ->name('cloud-at-cost.virtual-machines');

        Route::post('{server}', [VirtualMachineController::class, 'get'])
            ->name('cloud-at-cost.virtual-machines.get');

        Route::patch('{server}', [VirtualMachineController::class, 'update'])
            ->name('cloud-at-cost.virtual-machines.update');

        Route::delete('{server}', [VirtualMachineController::class, 'delete'])
            ->name('cloud-at-cost.virtual-machines.delete');
    });
});
