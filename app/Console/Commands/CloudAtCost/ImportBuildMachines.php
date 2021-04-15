<?php

namespace App\Console\Commands\CloudAtCost;

use App\DataTransfer\CloudAtCost\OperatingSystem;
use App\DataTransfer\CloudAtCost\ServerClassification;
use App\Models\CloudAtCost\Server\Platform;
use App\Services\CloudAtCost\PanelClient;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class ImportBuildMachines extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cloud-at-cost:import-build-machines';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports all of the Build Machine information';

    private PanelClient $client;

    private array $headers = [
        "User-Agent" => "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/84.0.4147.105 Safari/537.36",
    ];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Client $client, CookieJar $cookieJar)
    {
        parent::__construct();
        $this->client = new PanelClient(
            config('cloud-at-cost.panel.username'),
            config('cloud-at-cost.panel.password'),
            $client,
            $cookieJar,
        );
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->findBuildButtons()
            ->each(fn(ServerClassification $classification) => $this->hydrateOperatingSystems($classification))
            ->each(fn(ServerClassification $classification) => $this->saveUpdates($classification));

        return 0;
    }

    private function findBuildButtons(): Collection
    {
        $data = collect();
        $contents = $this->client->get('build');

        $matches = [];
        preg_match('/<form name=\'build\' [^>]+>(.*)<\/form>/i', $contents, $matches);

        $buttons = [];
        preg_match_all('/<button[^>]*value=[\'"]([A-Z0-9]*)[\'"][^>]*>([A-Z0-9 ()\-]+)<\/button>/i', $matches[1], $buttons);

        for($x = 1; $x < count($buttons); $x++) {
            $classification = new ServerClassification();
            $fullButton = $buttons[2][$x - 1];
            $names = [];

            preg_match('/Build to ([A-Z0-9 ]+)/i', $fullButton, $names);

            $classification->name = $names[1];

            $data->push($classification);
        }

        return $data;
    }

    private function hydrateOperatingSystems(ServerClassification $classification)
    {
        $contents = $this->client->get('build');

        $matches = [];
        preg_match('/<form name=\'build\' [^>]+>(.*)<\/form>/i', $contents, $matches);

        $tokens = [];
        preg_match('/<input.*name="token".*value=[\'"]([A-Z0-9]+)[\'"][^>]*>/i', $matches[1], $tokens);

        $buttons = [];
        preg_match('/<button[^>]*value=[\'"]([A-Z0-9]*)[\'"][^>]*>Build to ' . $classification->name .
            '[A-Z0-9 ()\-]*<\/button>/i', $matches[1], $buttons);

        $contents = $this->client->post(
            'build',
            [
                'infra' => $buttons[1],
                'token' => $tokens[1],
            ]);

        $select = [];
        preg_match('/<select[^>]*name=\'os\'[^>]*>(.*)<\/select>/i', $contents, $select);

        $options = [];
        preg_match_all('/<option[^>]*value=\'(\d+)\'[^>]*>([^<]+)<\/option>/i', $select[1], $options);

        foreach($options[0] as $key => $match) {
            $operatingSystem = new OperatingSystem();
            $operatingSystem->id = $options[1][$key];
            $operatingSystem->name = $options[2][$key];
            $classification->operatingSystems[] = $operatingSystem;
        }
    }

    private function saveUpdates(ServerClassification $classification)
    {
        $platform = Platform::firstOrNew([
            'name' => $classification->name
        ]);

        $platform->save();

        collect($classification->operatingSystems)
            ->each(function(OperatingSystem $operatingSystem) use($platform) {
                $os = $platform->operatingSystems()
                    ->firstOrNew([
                        'name' => $operatingSystem->name,
                    ]);

                $os->identifier = $operatingSystem->id;
                $os->save();
            });
    }
}
