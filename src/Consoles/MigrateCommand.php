<?php
namespace Garung\Database\Consoles;

use Garung\Database\Facades\BussinessDB;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class MigrateCommand extends Command
{
    protected function configure()
    {
        $this->setName('migrate')
            ->setDescription('Migrate all database migration')
            ->setHelp('php command migrate');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $notes = BussinessDB::run('database/migrations', 'up');
        if(!empty($notes)) {
            foreach ($notes as $key => $note) {
                $output->write($note, true);
            }
        }
    }
}
