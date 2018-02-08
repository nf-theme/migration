<?php

namespace Vicoders\Database\Connect;

use NF\Facades\App;

class NFDatabase
{
    public function __construct()
    {
        App::make('DBManager')->bootEloquent();
    }
}
