<?php
namespace App\Util\Log;

use Illuminate\Support\Facades\Facade;

class LoggerFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'logger';
    }
}