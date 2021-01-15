<?php

namespace Nethead\Menu\Items;

/**
 * Creates a Separator Menu item.
 * It doesn't have any menu functionality, it's just for decoration purposes.
 * Separators can be used to divide the menu Items sections.
 *
 * @package Nethead\Menu\Items
 */
class Separator extends Item {
    /**
     * As items can have child items, separators can't. If you want to setup a
     * more complex separator in HTML please use the appropriate renderer.
     * The child items are only considered to be a child menu items, not HTML elements.
     * Invoking this will throw an Exception.
     *
     * @param Item $child
     * @throws \Exception
     */
    public function addChild(Item $child)
    {
        throw new \Exception('Separators doesn\'t have parental rights!');
    }
}