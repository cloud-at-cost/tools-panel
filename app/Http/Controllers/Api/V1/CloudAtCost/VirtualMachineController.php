<?php

namespace App\Http\Controllers\Api\V1\CloudAtCost;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\CloudAtCost\PanelRequest;
use App\Http\Requests\Api\V1\CloudAtCost\VirtualMachine\DeleteRequest;
use App\Services\CloudAtCost\PanelClient;
use App\Services\CloudAtCost\VirtualMachineClient;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

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

    public function delete(DeleteRequest $request, string $server)
    {
        $client = new VirtualMachineClient(
            new PanelClient(
                $request->username(),
                $request->password(),
            )
        );

        if (!$client->delete($server)) {
            throw new BadRequestHttpException('Failed to delete the server');
        }

        return response()->noContent();
    }
}
