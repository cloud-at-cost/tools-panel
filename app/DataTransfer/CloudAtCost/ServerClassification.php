<?php


namespace App\DataTransfer\CloudAtCost;


class ServerClassification
{
    public string $name;

    /**
     * @var OperatingSystem[]
     */
    public array $operatingSystems = [];
}
