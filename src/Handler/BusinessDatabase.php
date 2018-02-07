<?php

namespace Garung\Database\Handler;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use League\Flysystem\Exception;

/**
 *
 */
class BusinessDatabase
{
    /**
     * The notes for the current operation.
     *
     * @var array
     */
    protected $notes = [];

    protected $namespace = '\Theme\Database\\';

    /**
     * Get all of the migration files in a given path.
     *
     * @param  string|array  $paths
     * @return array
     */
    public function run($path, $method = 'up')
    {
        ob_start();
        $name_files = scandir($path);
        unset($name_files[0]);
        unset($name_files[1]);

        if (empty($name_files)) {
            $this->note("<error>File don't exists in {$path} folder</error>", true);
            return $this->notes;
        }

        foreach ($name_files as $key => $name_file) {
            if (!is_dir($name_file)) {
                echo $name_file . "\n";
                $this->runMigration($name_file, $method);
            }
        }
        ob_get_clean();
        return $this->notes;
    }

    /**
     * Run "up" and "down" a migration instance.
     *
     * @param  string  $file
     * @param  string  $type_run
     * @return void
     */
    protected function runMigration($file, $method)
    {
        // First we will resolve a "real" instance of the migration class from this
        // migration file name. Once we have the instances we can run the actual
        // command such as "up" or "down", or we can just simulate the action.
        $this->note(var_dump($file));
        $migration = $this->resolve(
            $name = $this->getMigrationName($file)
        );
        $this->note("<comment>Migrating:</comment> {$name}");

        if (!method_exists($migration, $method)) {
            $this->note("<error>{$method}() doesn't exist:</error>");
            return false;
        } 

        $class = new $migration();
        $class->{$method}();

        if ($method === 'up') {
            $this->note("<info>Migrated:</info>  {$name}");
        } else {
            $this->note("<info>Rolled back:</info>  {$name}");
        }
    }

    /**
     * Get the name of the migration.
     *
     * @param  string  $path
     * @return string
     */
    public function getMigrationName($path)
    {
        return str_replace('.php', '', basename($path));
    }

    /**
     * Resolve a migration instance from a file.
     *
     * @param  string  $file
     * @return object
     */
    public function resolve($file)
    {
        $class      = Str::studly(implode('_', array_slice(explode('_', $file), 4)));
        $full_class = $this->namespace . $class;
        return $full_class;
    }

    /**
     * Raise a note event for the migrator.
     *
     * @param  string  $message
     * @return void
     */
    protected function note($message)
    {
        $this->notes[] = $message;
    }

    /**
     * Get the notes for the last operation.
     *
     * @return array
     */
    public function getNotes()
    {
        return $this->notes;
    }
}
