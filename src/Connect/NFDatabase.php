<?php

namespace Garung\Database\Connect;

use Garung\Database\Connect\DBManager;
use NF\Facades\App;

class NFDatabase
{
    public function __construct()
    {
        App::make(DBManager::class)->bootEloquent();
    }
}
