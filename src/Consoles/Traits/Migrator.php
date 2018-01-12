<?php

namespace Garung\Database\Consoles\Traits;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use NF\Facades\Storage;

trait Migrator
{
    /**
     * Resolve a migration instance from a file.
     *
     * @param  string  $file
     * @return object
     */
    public function resolve($file)
    {
        require_once getcwd() . DIRECTORY_SEPARATOR . $file['path'];
        $class = Str::studly(implode('_', array_slice(explode('_', $file['filename']), 4)));
        return new $class;
    }

    /**
     * Get all of the migration files in a given path.
     *
     * @param  string|array  $paths
     * @return array
     */
    public function getMigrationFiles($paths)
    {
        return Collection::make($paths)->flatMap(function ($path) {
            return Storage::listContents($path);
        })->filter(function ($item) {
            return $item['extension'] == 'php';
        });
    }

    /**
     * Get all of the migration paths.
     *
     * @return array
     */
    public function getMigrationPaths()
    {
        return ['/database/migrations'];
    }
}
