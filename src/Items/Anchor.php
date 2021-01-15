<?php

namespace Nethead\Menu\Items;

use Nethead\Menu\Menu;

/**
 * Anchor is a Menu Item that links to a place in the document
 * linking it with #fragment.
 *
 * @package Nethead\Menu\Items
 */
class Anchor extends External {
    /**
     * Anchor constructor.
     *
     * @param array $innerHTML
     *  Everything you want to display inside the Item's HTML.
     * @param Menu $menu
     *  Menu instance that this item will be bind to.
     * @param Item|null $parent
     *  Parent Item if this is a child.
     * @param string $anchorID
     *  HTML ID to be used as #fragment
     */
    public function __construct(array $innerHTML, string $anchorID, Menu $menu, Item $parent = null)
    {
        parent::__construct($innerHTML, '#' . $anchorID, $menu, $parent);
    }
}