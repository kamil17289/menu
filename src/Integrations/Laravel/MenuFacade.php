<?php

namespace Nethead\Menu\Integrations\Laravel;

use Illuminate\Support\Facades\Facade;

/**
 * Class MenuFacade
 * @package Nethead\Menu\Integrations\Laravel
 */
class MenuFacade extends Facade {
    /**
     * @return string
     */
    public static function getFacadeAccessor()
    {
        return 'menu';
    }
}