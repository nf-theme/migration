<?php

namespace NF\Database\Providers;

use Illuminate\Support\ServiceProvider;
use NF\Database\Connect\DBManager;
use Illuminate\Support\Collection;

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

        if ($this->app->config('is_plugin') === true) {
            if (method_exists($this, 'up')) {
                register_activation_hook($this->app->appPath() . DIRECTORY_SEPARATOR . $this->app->config('plugin_file'), [$this, 'up']);
            }
            // if (method_exists($this, 'down')) {
            //     register_uninstall_hook($this->app->appPath() . DIRECTORY_SEPARATOR . $this->app->config('plugin_file'), [$this, 'down']);
            // }
        }
    }

    public function up()
    {
        $migrations_folder_path = $this->app->appPath() . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR . 'migrations';
        $files                  = new Collection(scandir($migrations_folder_path));
        $files                  = $files->filter(function ($item) {
            return $item !== '.' && $item !== '..';
        });

        $files->each(function ($file) use ($migrations_folder_path) {
            $content = file_get_contents($migrations_folder_path . DIRECTORY_SEPARATOR . $file);
            preg_match('/.*class\s([a-zA-Z0-9]*)\s.*/', $content, $matches);
            $classname = $matches[1];
            $instance  = new $classname();
            $instance->up();
        });
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
