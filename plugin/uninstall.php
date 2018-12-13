<?php
/**
 * Reflaunt COS Plugin Uninstall
 *
 * @package Reflaunt COS Plugin\Uninstaller
 */
use Illuminate\Support\Collection;
$app = require_once __DIR__ . '/bootstrap/app.php';

$migrations_folder_path = plugin_dir_path(__FILE__) . 'database' . DIRECTORY_SEPARATOR . 'migrations';
$files                  = new Collection(scandir($migrations_folder_path));
$files                  = $files->filter(function ($item) {
    return $item !== '.' && $item !== '..';
});


$files->each(function ($file) use ($migrations_folder_path) {
    $content = file_get_contents($migrations_folder_path . DIRECTORY_SEPARATOR . $file);
    preg_match('/.*class\s([a-zA-Z0-9]*)\s.*/', $content, $matches);
    $classname = $matches[1];
    $instance  = new $classname();
    $instance->down();
});