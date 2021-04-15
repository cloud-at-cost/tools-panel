<?php


namespace App\DOM\CloudAtCost;


use App\Enumerations\VirtualMachine\State;

class VirtualMachineListDOM
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

    private string $body;

    public function __construct(string $body)
    {
        $this->body = $body;
        $this->parse();
    }

    private function parse()
    {
        $this->deriveNameDetails();
        $this->deriveOperatingSystem();
        $this->deriveIpAddresses();
        $this->deriveCPU();
        $this->deriveRAM();
        $this->deriveDisk();
        $this->deriveCloudPROVersion();
    }

    private function deriveNameDetails(): void
    {
        $regex = '/<td[^>]+id=\'PanelTitle_(\d+)\'><font color=\'(green|#d9534f)\'><i[^>]+><\/i><\/font>&nbsp;&nbsp;([^<]+)<\/td>/i';
        $matches = [];
        preg_match(
            $regex,
            $this->body,
            $matches
        );

        [, $this->identifier, $colour, $this->name] = $matches;

        $this->status = $colour === 'green' ? State::UP : State::DOWN;
    }

    private function deriveOperatingSystem(): void
    {
        $regex = '/Current OS:<\/td><td>([^<]+)<i/i';
        $matches = [];
        preg_match(
            $regex,
            $this->body,
            $matches
        );

        [, $this->operatingSystem] = $matches;
    }

    private function deriveIpAddresses(): void
    {
        $regex = '/IPv4:<\/td><td>([^<]+)<\/td>/i';
        $matches = [];
        preg_match(
            $regex,
            $this->body,
            $matches
        );

        [, $this->ipV4] = $matches;

        $regex = '/IPv6:<\/td><td>([^<]+)<a/i';
        $matches = [];
        preg_match(
            $regex,
            $this->body,
            $matches
        );

        [, $this->ipV6] = $matches;
    }

    private function deriveCPU(): void
    {
        $regex = '/(\d+) CPU:<\/td><td><div class=\'progress\'><div class=\'progress-bar progress-bar-success\' role=\'progressbar\' aria-valuenow=\'\d+\' aria-valuemin=\'\d+\' aria-valuemax=\'\d+\' style=\'width: (\d+)%;\'>/i';
        $matches = [];
        preg_match(
            $regex,
            $this->body,
            $matches
        );

        [, $this->numberOfCPUs, $this->cpuUsage] = $matches;
    }

    private function deriveRAM(): void
    {
        $regex = '/(\d+)MB RAM:<\/td><td><div class=\'progress\'><div class=\'progress-bar progress-bar-success\' role=\'progressbar\' aria-valuenow=\'\d+\' aria-valuemin=\'\d+\' aria-valuemax=\'\d+\' style=\'width: (\d+)%;\'>/i';
        $matches = [];
        preg_match(
            $regex,
            $this->body,
            $matches
        );

        [, $this->ramInMB, $this->ramUsage] = $matches;
    }

    private function deriveDisk(): void
    {
        $regex = '/(\d+)GB SSD:<\/td><td><div class=\'progress\'><div class=\'progress-bar progress-bar-success\' role=\'progressbar\' aria-valuenow=\'\d+\' aria-valuemin=\'\d+\' aria-valuemax=\'\d+\' style=\'width: (\d+)%;\'>/i';
        $matches = [];
        preg_match(
            $regex,
            $this->body,
            $matches
        );

        [, $this->diskInGB, $this->diskUsage] = $matches;
    }

    private function deriveCloudPROVersion()
    {
        $regex = '/(CloudPRO v\d+)/i';
        $matches = [];
        preg_match(
            $regex,
            $this->body,
            $matches
        );

        [, $this->version] = $matches;
    }
}
