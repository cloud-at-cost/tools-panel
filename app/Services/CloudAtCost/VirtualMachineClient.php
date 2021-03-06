<?php

namespace App\Services\CloudAtCost;

use App\DataTransfer\CloudAtCost\VirtualMachine;
use App\DOM\CloudAtCost\VirtualMachineListDOM;
use App\Enumerations\VirtualMachine\PowerState;
use Illuminate\Support\Collection;
use KubAT\PhpSimple\HtmlDomParser;

class VirtualMachineClient
{
    private PanelClient $client;

    public function __construct(PanelClient $client)
    {
        $this->client = $client;
    }

    public function find(string $server): ?VirtualMachine
    {
        return $this->retrieve()
            ->first(fn(VirtualMachine $virtualMachine) => $virtualMachine->identifier == $server);
    }

    public function updateState(VirtualMachine $virtualMachine, string $state)
    {
        $this->client->get(
            $this->configUrl('powerCycle.php'),
            [
                'sid' => $virtualMachine->identifier,
                'vmname' => $virtualMachine->vmname,
                'cycle' => PowerState::toCloudAtCost($state)
            ]
        );
    }

    /**
     * @return VirtualMachine[]
     */
    public function retrieve(): Collection
    {
        $body = $this->client->post('', [
            'search' => null,
            'limit' => 200,
            'filter' => 'All',
        ]);

        $document = (new HtmlDomParser())->str_get_html($body);

        $virtualMachines = collect();

        foreach($document->find('.panel') as $panel) {
            $name = $panel->find('[id*=Panel]');

            if(empty($name)) {
                continue;
            }

            $dom = new VirtualMachineListDOM($panel->outertext);
            $virtualMachine = new VirtualMachine();

            foreach(array_keys(get_class_vars(VirtualMachine::class)) as $key) {
                $virtualMachine->$key = trim($dom->$key);
            }

            $virtualMachines->push($virtualMachine);
        }

        return $virtualMachines;
    }

    public function delete(string $server): bool
    {
        $response = $this->client->get(
            $this->configUrl("serverdeletecloudpro.php"), [
                'sid' => $server,
                'reserve' => false,
            ],
            true
        );

        return $response !== 'failed';
    }

    private function configUrl(string $url): string
    {
        return "/panel/_config/$url";
    }
}
