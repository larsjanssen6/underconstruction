<?php

namespace LarsJanssen\UnderConstruction\Facades;

use Illuminate\Support\Facades\Facade;

class TransFormer extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'TransFormer';
    }
}
