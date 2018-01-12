<?php 

namespace Garung\Database\Providers;

use Illuminate\Support\ServiceProvider;
/**
 * Class DatabaseProvider
 */
class DatabaseProvider extends ServiceProvider
{
	public function register()
	{
		// don't have anything to handle
	}

	public function registerCommand() {
		return [
            \Garung\Database\Consoles\PublishCommand::class,
            \Garung\Database\Consoles\MigrateCommand::class,
            \Garung\Database\Consoles\MigrateRollbackCommand::class,
            \Garung\Database\Consoles\MakeMigrationCommand::class
        ];
	}
}