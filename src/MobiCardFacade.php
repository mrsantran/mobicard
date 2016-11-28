<?php

namespace SanTran\MobiCard;

use Illuminate\Support\Facades\Facade;

class MobiCardFacade extends Facade
{

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'mobicard';
    }

}
