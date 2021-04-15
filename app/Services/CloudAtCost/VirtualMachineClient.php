<?php


namespace App\Services\CloudAtCost;


use App\DataTransfer\CloudAtCost\VirtualMachine;
use App\DOM\CloudAtCost\VirtualMachineListDOM;
use Illuminate\Support\Collection;
use KubAT\PhpSimple\HtmlDomParser;

class VirtualMachineClient
{
    private PanelClient $client;

    public function __construct(PanelClient $client)
    {
        $this->client = $client;
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
}
