<?php


namespace App\DataTransfer\CloudAtCost;


use App\Enumerations\VirtualMachine\PowerState;
use App\Enumerations\VirtualMachine\State;

class VirtualMachine
{
    public string $identifier;
    public string $vmname;
    public string $name;
    public string $status;
    public string $operatingSystem;
    public string $ipV4;
    public string $ipV6;

    public int $numberOfCPUs;
    public int $cpuUsage;

    public int $ramInMB;
    public int $ramUsage;

    public int $diskInGB;
    public int $diskUsage;

    public string $version;

    public function shouldToggleState(string $state): bool
    {
        switch ($this->status) {
            case State::UP:
                return $state !== PowerState::TURN_ON;
            case State::DOWN:
                return $state !== PowerState::SHUT_DOWN;
        }
    }
}
