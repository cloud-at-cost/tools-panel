<?php

namespace Database\Seeders;

use App\Models\CloudAtCost\Server\Platform;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class PlatformOperatingSystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        collect([
            'CloudPro V1' => $this->cloudProV1(),
            'CloudPro V3' => $this->cloudProV3(),
            'CloudPro V4' => $this->cloudProV4(),
        ])->each(fn($operatingSystems, $platform) => $this->build($platform, $operatingSystems));
    }

    private function build(string $platform, Collection $operatingSystems)
    {
        $p = Platform::firstOrCreate([
            'name' => $platform,
        ]);

        $operatingSystems->each(function($operatingSystem, $identifier) use($p) {
            $os = $p->operatingSystems()
                ->firstOrNew([
                    'name' => $operatingSystem,
                ]);

            $os->identifier = $identifier;
            $os->save();
        });
    }

    private function cloudProV1(): Collection
    {
        return collect([
            1 => 'CentOS 6.7 64bit',
            15 => 'CentOS 7 64bit',
            3 => 'Debian 8 64bit',
            18 => 'FreeBSD 10.1 64bit',
            16 => 'Ubuntu 14.04.5 LTS 64bit',
            14 => 'Windows Server 2012 R2',
            13 => 'Windows Server 2008 R2',
            4 => 'Windows 7',
        ]);
    }

    private function cloudProV3(): Collection
    {
        return collect([
            33 => 'CentOS 6.7 64bit',
            42 => 'CentOS 6.9 64bit',
            38 => 'CentOS 7 64bit',
            49 => 'CentOS 7.5 64bit',
            89 => 'CentOS 8.0 64bit',
            34 => 'Debian 8 64bit',
            41 => 'Debian 8.8 64bit',
            88 => 'Debian 9.11 64bit',
            50 => 'Debian 9.4 64bit',
            40 => 'FreeBSD 10.1 64bit',
            87 => 'FreeBSD 12 64bit',
            54 => 'Turnkey-APACHE-TOMCAT',
            55 => 'Turnkey-LAMP',
            56 => 'Turnkey-LAPP',
            57 => 'Turnkey-MYSQL',
            58 => 'Turnkey-NODEJS',
            59 => 'Turnkey-POSTGRES',
            60 => 'Turnkey-WORDPRESS',
            39 => 'Ubuntu 14.04.5 LTS 64bit',
            43 => 'Ubuntu 16.04.2 LTS 64bit',
            51 => 'Ubuntu 18.04 LTS 64bit',
            150 => 'Windows 10 2004 64bit',
            37 => 'Windows 2012 R2 64bit',
            48 => 'Windows 2016 Standard 64bit',
            86 => 'Windows Server 2016',
        ]);
    }

    private function cloudProV4(): Collection
    {
        return collect([
            126 => 'Windows Server 2016',
            142 => 'Ubuntu 18 LinkClone Test',
            143 => 'CentOS 8.1',
            144 => 'CentOS 8.0 64bit LC',
            146 => 'Ubuntu 18.04 LTS 64bit LC',
            145 => 'Debian 9.11 64bit LC',
            148 => 'MacOS Catalina 10.15',
            149 => 'Ubuntu 14.04.5 LTS 64bit',
            147 => 'MacOS Catalina 10.15',
        ]);
    }

}
