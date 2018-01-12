<?php

namespace Garung\Database\Providers;

use Garung\Database\Connect\DBManager;
use Illuminate\Support\ServiceProvider;

/**
 * Class DatabaseProvider
 */
class DatabaseServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('DBManager', function ($app) {
            return new DBManager;
        });
    }

    public function registerCommand()
    {
        return [
            \Garung\Database\Consoles\PublishCommand::class,
            \Garung\Database\Consoles\MigrateCommand::class,
            \Garung\Database\Consoles\MigrateRollbackCommand::class,
            \Garung\Database\Consoles\MakeMigrationCommand::class,
        ];
    }
}
