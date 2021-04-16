<?php

namespace App\Enumerations\VirtualMachine;

class PowerState
{
    const REBOOT = 'REBOOT';
    const TURN_ON = 'BOOTUP';
    const SHUT_DOWN = 'SHUTDOWN';

    public static function toCloudAtCost(string $state): int
    {
        switch($state) {
            case static::REBOOT:
                return 2;
            case static::TURN_ON:
                return 1;
            case static::SHUT_DOWN:
                return 0;
        }
    }
}
