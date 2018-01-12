<?php
namespace Garung\Database\Consoles;

use Garung\Database\Consoles\Traits\Migrator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MigrateCommand extends Command
{
    use Migrator;

    protected function configure()
    {
        $this->setName('migrate')
            ->setDescription('Migrate all database migration')
            ->setHelp('php command migrate');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $paths = $this->getMigrationPaths();
        $files = $this->getMigrationFiles($paths);

        $files->each(function ($file) {
            $instance = $this->resolve($file);
            call_user_func([$instance, 'up']);
        });
    }
}
