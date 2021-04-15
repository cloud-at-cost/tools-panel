<?php


namespace App\DOM\CloudAtCost;


use App\Enumerations\VirtualMachine\State;

class VirtualMachineListDOM
{
    private string $body;

    public function __construct(string $body)
    {
        $this->body = $body;
        $this->parse();
    }

    public string $identifier;
    public string $name;
    public bool $isUp;
    public string $operatingSystem;
    public string $ipV4;
    public string $ipV6;

    public int $numberOfCPUs;
    public int $cpuUsage;

    public int $ramInMB;
    public int $ramUsage;

    public int $diskInGB;
    public int $diskUsage;

    private function parse()
    {
        $this->deriveNameDetails();
        $this->deriveOperatingSystem();
        $this->deriveIpAddresses();
        $this->deriveCPU();
        $this->deriveRAM();
        $this->deriveDisk();
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

        $this->isUp = $colour === State::UP;
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
}
