<?php

namespace Nethead\Menu\Items;

use Nethead\Menu\Menu;

/**
 * Class Separator
 * @package Nethead\Menu\Items
 */
class Separator extends Item {
    /**
     * @return string
     */
    public function render(): string
    {
        Menu::getRenderer()->render($this);
    }
}