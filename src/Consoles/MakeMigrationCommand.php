<?php

namespace Vicoders\Database\Consoles;

use NF\CompileBladeString\Facade\BladeCompiler;
use NF\Facades\Storage;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class MakeMigrationCommand extends Command
{
    const CREATE = 'create';
    const UPDATE = 'update';
    protected function configure()
    {
        $this->setName('make:migration {name}')
            ->setDescription('Create a migration')
            ->setHelp('php command make:migration {{name}}')
            ->addArgument('name', InputArgument::REQUIRED, 'Name')
            ->addOption('create', null, InputOption::VALUE_OPTIONAL, 'Create new table')
            ->addOption('table', null, InputOption::VALUE_OPTIONAL, 'Update existing table');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($input->getOption('create') == null && $input->getOption('table') == null) {
            $output->writeln('<error>You have to provide option --create or --update</error>');
            exit;
        } else {
            if ($input->getOption('create') != null) {
                $mode       = self::CREATE;
                $table_name = snake_case($input->getOption('create'));
            } else {
                $mode       = self::UPDATE;
                $table_name = snake_case($input->getOption('table'));
            }
        }
        $name           = snake_case($input->getArgument('name'));
        $class_name     = studly_case($input->getArgument('name'));
        $path           = '/database' . DIRECTORY_SEPARATOR . 'migrations';
        $file_extension = '.php';
        $file_path      = $this->getPath($name, $path, $file_extension);

        if ($mode == self::CREATE) {
            $stuff = <<<'EOT'
use Illuminate\Database\Schema\Blueprint;
use Vicoders\Database\Connect\NFDatabase;
use Illuminate\Database\Capsule\Manager as Capsule;

class {{$class_name}} extends NFDatabase
{
    private $table = '{{ $table_name }}';

    public function up()
    {
        global $wpdb;
        $table_name_with_prefix = $wpdb->prefix . $this->table;
        if (!Capsule::Schema()->hasTable($table_name_with_prefix)) {
            Capsule::Schema()->create($table_name_with_prefix, function($table){
                $table->increments('id');

                $table->timestamps();
            });
        }
    }

    public function down() {
        global $wpdb;
        $table_name_with_prefix = $wpdb->prefix . $this->table;
        if (Capsule::Schema()->hasTable($table_name_with_prefix)) {
            Capsule::Schema()->drop($table_name_with_prefix);
        }
    }
}
EOT;

        } else {
            $stuff = <<<'EOT'
use Illuminate\Database\Schema\Blueprint;
use Vicoders\Database\Connect\NFDatabase;
use Illuminate\Database\Capsule\Manager as Capsule;

class {{$class_name}} extends NFDatabase
{
    private $table = '{{ $table_name }}';

    public function up()
    {
        global $wpdb;
        $table_name_with_prefix = $wpdb->prefix . $this->table;
        if (Capsule::Schema()->hasTable($table_name_with_prefix)) {
            Capsule::Schema()->table($table_name_with_prefix, function($table){

            });
        }
    }

    public function down() {

    }
}
EOT;

        }
        $compiled = BladeCompiler::compileString($stuff, compact('class_name', 'table_name'));
        $compiled = <<<EOT
<?php

{$compiled}

EOT;

        if (Storage::has($file_path)) {
            $output->write("<error>File exists: {$file_path}</error>", true);
            exit;
        }

        Storage::write($file_path, $compiled);
        @exec('composer dump-autoload');
        $output->writeln("<info>{$file_path} is created</info>", true);
    }

    /**
     * Get the date prefix for the migration.
     *
     * @return string
     */
    protected function getDatePrefix()
    {
        return date('Y_m_d_His');
    }

    /**
     * Get the full path to the migration.
     *
     * @param  string  $name
     * @param  string  $path
     * @return string
     */
    protected function getPath($name, $path, $file_extension)
    {
        return $path . DIRECTORY_SEPARATOR . $this->getDatePrefix() . '_' . $name . $file_extension;
    }

    /**
     * Get the class name of a migration name.
     *
     * @param  string  $name
     * @return string
     */
    protected function getClassName($name)
    {
        return Str::studly($name);
    }
}
