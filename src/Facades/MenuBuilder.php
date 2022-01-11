<?php

namespace AzizSama\MenuBuilder\Facades;

use Illuminate\Support\Facades\Facade;

class MenuBuilder extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'menu-builder';
    }
}