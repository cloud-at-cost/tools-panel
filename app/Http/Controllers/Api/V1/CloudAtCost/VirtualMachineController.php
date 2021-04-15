<?php

namespace App\Http\Controllers\Api\V1\CloudAtCost;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\CloudAtCost\PanelRequest;
use App\Services\CloudAtCost\PanelClient;
use App\Services\CloudAtCost\VirtualMachineClient;
use Illuminate\Http\Request;

class VirtualMachineController extends Controller
{
    public function index(PanelRequest $request)
    {
        $client = new VirtualMachineClient(
            new PanelClient(
                $request->username(),
                $request->password(),
            )
        );

        return $client->retrieve();
    }
}
