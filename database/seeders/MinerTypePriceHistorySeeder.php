<?php

namespace Database\Seeders;

use App\Models\Miner\MinerType;
use Illuminate\Database\Seeder;

class MinerTypePriceHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        collect($this->minerDetails())
            ->each(fn($minerDetail, $key) => $this->process($key, $minerDetail));
    }

    /**
     * @param string $typeSlug
     * @param array $details
     */
    private function process(string $typeSlug, array $details)
    {
        $minerType = MinerType::whereSlug($typeSlug)->first();

        collect($details)
            ->each(fn($detail) => $this->addPrice($minerType, $detail));
    }

    /**
     * @param MinerType $minerType
     * @param $details
     */
    private function addPrice(MinerType $minerType, $details)
    {
        $history = $minerType->priceHistory()
            ->firstOrNew([
                'created_at' => $details['date'],
            ]);

        $history->price = $details['price'];
        $history->bitcoins_per_month = $details['bitcoins_per_month'];
        $history->save();
    }

    private function minerDetails(): array
    {
        return [
            'm1-miner' => $this->m1Miner(),
            'm2-miner' => $this->m2Miner(),
            'm5-miner' => $this->m5Miner(),
            'f10-miner' => $this->f10Miner(),
            'f20-miner' => $this->f20Miner(),
            'f40-miner' => $this->f40Miner(),
            'x60-miner' => $this->x60Miner(),
            'x70-miner' => $this->x70Miner(),
            'x80-miner' => $this->x80Miner(),
            'xl100-miner' => $this->xl100Miner(),
            'xl200-miner' => $this->xl200Miner(),
            'xl300-miner' => $this->xl300Miner(),
        ];
    }

    private function m1Miner(): array
    {
        return [
            [
                'price' => 62.5,
                'bitcoins_per_month' => 0.00013628,
                'date' => '2021-03-09',
            ],
            [
                'price' => 78,
                'bitcoins_per_month' => 0.00013628,
                'date' => '2021-03-12 18:45',
            ],
            [
                'price' => 80,
                'bitcoins_per_month' => 0.00014563,
                'date' => '2021-03-13 18:45',
            ],
            [
                'price' => 90,
                'bitcoins_per_month' => 0.00012142,
                'date' => '2021-03-17 16:00',
            ],
        ];
    }

    private function m2Miner(): array
    {
        return [
            [
                'price' => 125,
                'bitcoins_per_month' => 0.00027256,
                'date' => '2021-03-09',
            ],
            [
                'price' => 156,
                'bitcoins_per_month' => 0.00027256,
                'date' => '2021-03-12 18:45',
            ],
            [
                'price' => 160,
                'bitcoins_per_month' => 0.00029126,
                'date' => '2021-03-13 18:45',
            ],
            [
                'price' => 180,
                'bitcoins_per_month' => 0.00024284,
                'date' => '2021-03-17 16:00',
            ],
        ];
    }

    private function m5Miner(): array
    {
        return [
            [
                'price' => 250,
                'bitcoins_per_month' => 0.00054513,
                'date' => '2021-03-09',
            ],
            [
                'price' => 313,
                'bitcoins_per_month' => 0.00054513,
                'date' => '2021-03-12 18:45',
            ],
            [
                'price' => 400,
                'bitcoins_per_month' => 0.00072814,
                'date' => '2021-03-13 18:45',
            ],
            [
                'price' => 450,
                'bitcoins_per_month' => 0.000607110,
                'date' => '2021-03-17 16:00',
            ],
        ];
    }

    private function f10Miner(): array
    {
        return [
            [
                'price' => 500,
                'bitcoins_per_month' => 0.00115439,
                'date' => '2021-03-09',
            ],
            [
                'price' => 625,
                'bitcoins_per_month' => 0.00115439,
                'date' => '2021-03-12 18:45',
            ],
            [
                'price' => 800,
                'bitcoins_per_month' => 0.00145628,
                'date' => '2021-03-13 18:45',
            ],
            [
                'price' => 900,
                'bitcoins_per_month' => 0.00121422,
                'date' => '2021-03-17 16:00',
            ],
        ];
    }

    private function f20Miner(): array
    {
        return [
            [
                'price' => 1000,
                'bitcoins_per_month' => 0.00230878,
                'date' => '2021-03-09',
            ],
            [
                'price' => 1250,
                'bitcoins_per_month' => 0.00230878,
                'date' => '2021-03-12 18:45',
            ],
            [
                'price' => 1600,
                'bitcoins_per_month' => 0.00291256,
                'date' => '2021-03-13 18:45',
            ],
            [
                'price' => 1800,
                'bitcoins_per_month' => 0.00242844,
                'date' => '2021-03-17 16:00',
            ],
        ];
    }

    private function f40Miner(): array
    {
        return [
            [
                'price' => 2000,
                'bitcoins_per_month' => 0.00461757,
                'date' => '2021-03-09',
            ],
            [
                'price' => 2500,
                'bitcoins_per_month' => 0.00230878,
                'date' => '2021-03-12 18:45',
            ],
            [
                'price' => 3200,
                'bitcoins_per_month' => 0.00582511,
                'date' => '2021-03-13 18:45',
            ],
            [
                'price' => 3600,
                'bitcoins_per_month' => 0.00485689,
                'date' => '2021-03-17 16:00',
            ],
        ];
    }

    private function x60Miner(): array
    {
        return [
            [
                'price' => 3000,
                'bitcoins_per_month' => 0.00735925,
                'date' => '2021-03-09',
            ],
            [
                'price' => 4200,
                'bitcoins_per_month' => 0.00735925,
                'date' => '2021-03-12 18:45',
            ],
            [
                'price' => 4800,
                'bitcoins_per_month' => 0.00873767,
                'date' => '2021-03-13 18:45',
            ],
            [
                'price' => 5400,
                'bitcoins_per_month' => 0.00728533,
                'date' => '2021-03-17 16:00',
            ],
        ];
    }

    private function x70Miner(): array
    {
        return [
            [
                'price' => 3500,
                'bitcoins_per_month' => 0.00858579,
                'date' => '2021-03-09',
            ],
            [
                'price' => 4900,
                'bitcoins_per_month' => 0.00858579,
                'date' => '2021-03-12 18:45',
            ],
            [
                'price' => 5600,
                'bitcoins_per_month' => 0.01019395,
                'date' => '2021-03-13 18:45',
            ],
            [
                'price' => 6300,
                'bitcoins_per_month' => 0.00849955,
                'date' => '2021-03-17 16:00',
            ],
        ];
    }

    private function x80Miner(): array
    {
        return [
            [
                'price' => 4000,
                'bitcoins_per_month' => 0.0098123,
                'date' => '2021-03-09',
            ],
            [
                'price' => 5600,
                'bitcoins_per_month' => 0.0098123,
                'date' => '2021-03-12 18:45',
            ],
            [
                'price' => 6400,
                'bitcoins_per_month' => 0.01165023,
                'date' => '2021-03-13 18:45',
            ],
            [
                'price' => 7200,
                'bitcoins_per_month' => 0.00971378,
                'date' => '2021-03-17 16:00',
            ],
        ];
    }

    private function xl100Miner(): array
    {
        return [
            [
                'price' => 8000,
                'bitcoins_per_month' => 0.01456278,
                'date' => '2021-03-13 18:45',
            ],
            [
                'price' => 9000,
                'bitcoins_per_month' => 0.01214222,
                'date' => '2021-03-17 16:00',
            ],
        ];
    }

    private function xl200Miner(): array
    {
        return [
            [
                'price' => 16000,
                'bitcoins_per_month' => 0.02912556,
                'date' => '2021-03-13 18:45',
            ],
            [
                'price' => 18000,
                'bitcoins_per_month' => 0.02428444,
                'date' => '2021-03-17 16:00',
            ],
        ];
    }

    private function xl300Miner(): array
    {
        return [
            [
                'price' => 24000,
                'bitcoins_per_month' => 0.04368834,
                'date' => '2021-03-13 18:45',
            ],
            [
                'price' => 27000,
                'bitcoins_per_month' => 0.03642666,
                'date' => '2021-03-17 16:00',
            ],
        ];
    }
}
