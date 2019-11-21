<?php

namespace Nethead\Menu\Items;

use Nethead\Menu\Menu;

/**
 * Class Separator
 * @package Nethead\Menu\Items
 */
class Separator extends Item {
    /**
     * @param Item $child
     * @throws \Exception
     */
    public function addChild(Item $child)
    {
        throw new \Exception('Separators doesn\'t have parental rights!');
    }

    /**
     * @return string
     */
    public function render(): string
    {
        Menu::getRenderer()->render($this);
    }
}