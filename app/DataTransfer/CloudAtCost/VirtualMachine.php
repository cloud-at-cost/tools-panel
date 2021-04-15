<?php


namespace App\DataTransfer\CloudAtCost;


class VirtualMachine
{
    public string $identifier;
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
}
