<?php

namespace Nethead\Menu\Items;

use Nethead\Menu\Menu;

/**
 * Class Special is for creating the Menu Items that use schemes
 * different than http://, for example tel: or mailto:
 *
 * @package Nethead\Menu\Items
 */
class Special extends External {
    /**
     * Special constructor.
     *
     * @param array $innerHTML
     *  Everything you want to display inside the Item's HTML.
     * @param Menu $menu
     *  Menu instance that this item will be bind to.
     * @param Item|null $parent
     *  Parent Item if this is a child.
     * @param string $scheme
     *  Scheme for the link, for example tel: or mailto:
     * @param string $href
     *  Telephone number for tel:, e-mail address for mailto:, etc.
     */
    public function __construct(array $innerHTML, string $scheme, string $href, Menu $menu, Item $parent = null)
    {
        parent::__construct($innerHTML, "$scheme:$href", $menu, $parent);
    }
}