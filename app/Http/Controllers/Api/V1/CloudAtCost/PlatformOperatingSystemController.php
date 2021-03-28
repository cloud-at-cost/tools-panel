<?php

namespace App\Http\Controllers\Api\V1\CloudAtCost;

use App\Http\Controllers\Controller;
use App\Models\CloudAtCost\Server\Platform;
use App\Models\CloudAtCost\Server\PlatformOperatingSystem;
use Illuminate\Http\Request;

class PlatformOperatingSystemController extends Controller
{
    public function index(Platform $platform)
    {
        return $platform->operatingSystems
            ->sortBy(fn(PlatformOperatingSystem $operatingSystem) => $operatingSystem->name)
            ->values()
            ->transform(fn(PlatformOperatingSystem $operatingSystem) => [
                'id' => $operatingSystem->identifier,
                'name' => $operatingSystem->name
            ]);
    }
}
