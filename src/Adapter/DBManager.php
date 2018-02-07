<?php

namespace Garung\Database\Adapter;

use Garung\Database\Adapter\DBAdapter;
use NF\Facades\App;

class DBManager
{
    public function __construct($plugin_file = __FILE__)
    {
        App::make(DBAdapter::class)->bootEloquent();

        // if (method_exists($this, 'up')) {
        //     register_activation_hook($plugin_file, [$this, 'up']);
        // }
        // if (method_exists($this, 'down')) {
        //     register_uninstall_hook($plugin_file, [$this, 'down']);
        // }
    }
}
