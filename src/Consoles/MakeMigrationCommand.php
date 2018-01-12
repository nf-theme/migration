<?php

namespace Garung\Database\Consoles;

use NF\CompileBladeString\Facade\BladeCompiler;
use NF\Facades\Storage;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Illuminate\Support\Collection;

class MakeMigrationCommand extends Command
{
    protected function configure()
    {
        $this->setName('make:migration')
            ->setDescription('Create a migration')
            ->setHelp('php command make:migration {{name_table}}')
            ->addArgument('name_migrate', InputArgument::REQUIRED, 'Name of migration file.')
            ->addArgument('name_table', InputArgument::REQUIRED, 'Name of migration file.')
            ->addOption('override', null, InputOption::VALUE_OPTIONAL, 'Override exist file.', null);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name_table     = mb_strtolower($input->getArgument('name_table'));
        $name           = $input->getArgument('name_migrate');
        $path           = '/database' . DIRECTORY_SEPARATOR . 'migrations';
        $file_name      = studly_case($name);
        $file_extension = '.php';
        $file_path      = $this->getPath($file_name, $path, $file_extension);
        $migrateName    = str_slug($name, '_');

        $migrate_blade = <<<'EOT'
namespace Theme\Database;

use Illuminate\Database\Schema\Blueprint;
use Garung\Database\Connect\NFDatabase;
use Illuminate\Database\Capsule\Manager as Capsule;

class {{$file_name}} extends NFDatabase
{
    public $table = '{{ $name_table }}';

    public function __construct()
    {
        parent::__construct();
    }

    public function up()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . $this->table;
        if (!Capsule::Schema()->hasTable($table_name)) {
            Capsule::Schema()->create($table_name, function($table){
                $table->increments('id');

                $table->timestamps();
            });
        }
    }

    public function down() {
        global $wpdb;
        $table_name = $wpdb->prefix . $this->table;
        if (Capsule::Schema()->hasTable($table_name)) {
            Capsule::Schema()->drop($table_name);
        }
    }
}
EOT;

        $compiled = BladeCompiler::compileString($migrate_blade, ['file_name' => $file_name, 'name_table' => $name_table]);
        $compiled = <<<EOT
<?php

{$compiled}

EOT;
        if (Storage::has($file_path)) {
            if ($input->getOption('override') !== false) {
                Storage::delete($file_path);
            } else {
                $output->write("<error>File exists: {$file_path}</error>", true);
                return false;
            }
        }

        Storage::write($file_path, $compiled);
        try {
            exec('composer dump-autoload -o');
        } catch(Exception $e) {
            throw new Exception('Have a issue occur when composer dump-autoload');
        }
        $output->write("<info>{$file_name} is created success.</info>", true);
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
        return $path . '/' . $this->getDatePrefix() . '_' . $name . $file_extension;
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
