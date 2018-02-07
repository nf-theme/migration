<?php 

namespace Garung\Database\Facades;

use Illuminate\Support\Facades\Facade;

/**
* 
*/
class BussinessDB extends Facade
{
	/**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return new \Garung\Database\Handler\BusinessDatabase;
    }
}