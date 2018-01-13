<?php
namespace Garung\Database\Consoles;

use Garung\Database\Consoles\Traits\Migrator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MigrateRollbackCommand extends Command
{
    use Migrator;
    protected function configure()
    {
        $this->setName('migrate:rollback')
            ->setDescription('Rollback all database migrations')
            ->setHelp('php command migration:rollback');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $paths = $this->getMigrationPaths();
        $files = $this->getMigrationFiles($paths);

        $files->sortByDesc('filename')->each(function ($file) use ($output) {
            $instance = $this->resolve($file);
            call_user_func([$instance, 'down']);
            $output->writeln("<info>Rolled back:</info> {$file['filename']}");
        });
    }
}
