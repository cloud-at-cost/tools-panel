<?php

namespace App\Providers;

use App\Actions\Jetstream\DeleteUser;
use Illuminate\Support\ServiceProvider;
use Laravel\Jetstream\Jetstream;

class JetstreamServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->configurePermissions();

        Jetstream::deleteUsersUsing(DeleteUser::class);
    }

    /**
     * Configure the permissions that are available within the application.
     *
     * @return void
     */
    protected function configurePermissions()
    {
        Jetstream::defaultApiTokenPermissions([
            'read',
        ]);

        Jetstream::permissions([
            /* global permissions */
            'create',
            'read',
            'update',
            'delete',

            /* miner specific permissions */
            'miner:create',
            'miner:read',
            'miner:update',
            'miner:delete',

            /* virtual machine specific permissions */
            'virtual-machine:create',
            'virtual-machine:read',
            'virtual-machine:update',
            'virtual-machine:delete',
        ]);
    }
}
