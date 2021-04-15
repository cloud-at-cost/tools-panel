<?php

namespace App\Console\Commands\CloudAtCost;

use App\DataTransfer\CloudAtCost\VirtualMachine;
use App\Services\CloudAtCost\PanelClient;
use App\Services\CloudAtCost\VirtualMachineClient;
use Illuminate\Console\Command;

class RetrieveMachines extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cloud-at-cost:retrieve-machines';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Retrieves machines for the configured user';

    private VirtualMachineClient $client;


    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->client = new VirtualMachineClient(
            new PanelClient(
                config('cloud-at-cost.panel.username'),
                config('cloud-at-cost.panel.password'),
            )
        );

        $this->table(
            array_keys(get_class_vars(VirtualMachine::class)),
            $this->client->retrieve()
                ->transform(fn(VirtualMachine $virtualMachine) => (array)$virtualMachine)
                ->toArray()
        );

        return 0;
    }
}
