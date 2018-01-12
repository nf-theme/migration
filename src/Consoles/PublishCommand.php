<?php

namespace Garung\Database\Consoles;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PublishCommand extends Command
{
    protected function configure()
    {
        $this->setName('migration:publish')
            ->setDescription('Publish configuration for garung/migration');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!is_dir('database')) {
            mkdir('database', 0755);
        }

        if (!is_dir('database/factories')) {
            mkdir('database/factories', 0755);
        }

        if (!is_dir('database/migrations')) {
            mkdir('database/migrations', 0755);
        }

        if (!is_dir('database/seeders')) {
            mkdir('database/seeders', 0755);
        }
        
        if (!file_exists('database/migrations/2018_01_01_000000_CreateTestTable.php')) {
            copy('db/resources/database/migrations/2018_01_01_000000_CreateTestTable.php', 'database/migrations/2018_01_01_000000_CreateTestTable.php');
        }
    }
}
