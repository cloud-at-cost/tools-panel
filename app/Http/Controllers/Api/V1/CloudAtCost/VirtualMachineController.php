<?php

namespace App\Http\Controllers\Api\V1\CloudAtCost;

use App\Enumerations\VirtualMachine\State;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\CloudAtCost\PanelRequest;
use App\Http\Requests\Api\V1\CloudAtCost\VirtualMachine\DeleteRequest;
use App\Http\Requests\Api\V1\CloudAtCost\VirtualMachine\UpdateRequest;
use App\Services\CloudAtCost\PanelClient;
use App\Services\CloudAtCost\VirtualMachineClient;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class VirtualMachineController extends Controller
{
    public function index(PanelRequest $request)
    {
        return $this->buildApiClient($request)->retrieve();
    }

    public function get(PanelRequest $request, string $server)
    {
        return $this->buildApiClient($request)
            ->find($server);
    }

    public function update(UpdateRequest $request, string $server)
    {
        $client = $this->buildApiClient($request);
        $virtualMachine = $client->find($server);

        if(!$virtualMachine) {
            throw new NotFoundHttpException("Server not found");
        }

        $state = $request->get('state');
        if(isset($state) && $virtualMachine->shouldToggleState($state)) {
            $client->updateState($virtualMachine, $state);
        }

        return $client->retrieve($server);
    }

    public function delete(DeleteRequest $request, string $server)
    {
        $client = $this->buildApiClient($request);

        if (!$client->delete($server)) {
            throw new BadRequestHttpException('Failed to delete the server');
        }

        return response()->noContent();
    }

    private function buildApiClient(PanelRequest $request): VirtualMachineClient
    {
        return new VirtualMachineClient(
            new PanelClient(
                $request->username(),
                $request->password(),
            )
        );
    }
}
