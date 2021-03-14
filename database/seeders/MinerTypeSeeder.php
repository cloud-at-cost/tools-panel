<?php

namespace Database\Seeders;

use App\Models\Miner\MinerType;
use Illuminate\Database\Seeder;

class MinerTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        collect([
            [
                'slug' => 'm1-miner',
                'name' => 'M1 Miner',
            ],
            [
                'slug' => 'm2-miner',
                'name' => 'M2 Miner',
            ],
            [
                'slug' => 'm5-miner',
                'name' => 'M5 Miner',
            ],
            [
                'slug' => 'f10-miner',
                'name' => 'F10 Miner',
            ],
            [
                'slug' => 'f20-miner',
                'name' => 'F20 Miner',
            ],
            [
                'slug' => 'f40-miner',
                'name' => 'F40 Miner',
            ],
            [
                'slug' => 'x60-miner',
                'name' => 'X60 Miner',
            ],
            [
                'slug' => 'x70-miner',
                'name' => 'X70 Miner',
            ],
            [
                'slug' => 'x80-miner',
                'name' => 'X80 Miner',
            ],
            [
                'slug' => 'xl100-miner',
                'name' => 'XL100 Miner',
            ],
            [
                'slug' => 'xl200-miner',
                'name' => 'XL200 Miner',
            ],
            [
                'slug' => 'xl300-miner',
                'name' => 'XL300 Miner',
            ],
        ])->each(fn(array $details) => MinerType::firstOrNew($details)->save());
    }
}
