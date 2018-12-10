<?php

namespace NF\Database\Providers;

use Illuminate\Support\ServiceProvider;
use NF\Database\Connect\DBManager;

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

        // check exist folders
        // if (!is_dir(get_stylesheet_directory() . '/database')) {
        //     mkdir(get_stylesheet_directory() . '/database', 0755);
        // }

        // if (!is_dir(get_stylesheet_directory() . '/database/migrations')) {
        //     mkdir(get_stylesheet_directory() . '/database/migrations', 0755);
        // }
    }

    public function registerCommand()
    {
        return [
            \NF\Database\Consoles\PublishCommand::class,
            \NF\Database\Consoles\MigrateCommand::class,
            \NF\Database\Consoles\MigrateRollbackCommand::class,
            \NF\Database\Consoles\MakeMigrationCommand::class,
        ];
    }
}
