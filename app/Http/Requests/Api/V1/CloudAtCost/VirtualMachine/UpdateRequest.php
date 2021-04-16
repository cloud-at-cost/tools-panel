<?php

namespace App\Http\Requests\Api\V1\CloudAtCost\VirtualMachine;

use App\Http\Requests\Api\V1\CloudAtCost\PanelRequest;

class UpdateRequest extends PanelRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check() && (
                $this->user()->tokenCan('virtual-machine:update')
                || $this->user()->tokenCan('update')
            );
    }
}
