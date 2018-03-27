<?php

namespace Vicoders\Database\Providers;

use Vicoders\Database\Connect\DBManager;
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

        if (!is_dir(get_stylesheet_directory() . '/database')) {
            mkdir(get_stylesheet_directory() . '/database', 0755);
        }

        if (!is_dir(get_stylesheet_directory() . '/database/migrations')) {
            mkdir(get_stylesheet_directory() . '/database/migrations', 0755);
        }
    }

    public function registerCommand()
    {
        return [
            \Vicoders\Database\Consoles\PublishCommand::class,
            \Vicoders\Database\Consoles\MigrateCommand::class,
            \Vicoders\Database\Consoles\MigrateRollbackCommand::class,
            \Vicoders\Database\Consoles\MakeMigrationCommand::class,
        ];
    }
}
