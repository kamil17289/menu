<?php

namespace Nethead\Menu\Items;

use Nethead\Menu\Menu;

/**
 * TextItem is a simple item which doesn't link to anything.
 * It's just text which can be used as not clickable base for dropdown menus.
 *
 * @package Nethead\Menu\Items
 */
class SimpleItem extends Item {
    /**
     * @var string
     */
    protected $innerHTML = [];

    /**
     * TextItem constructor.
     *
     * @param array $innerHTML
     *  Everything you want to display inside the Item's HTML.
     * @param Menu $menu
     *  Menu instance that this item will be bind to.
     * @param Item|null $parent
     *  Parent Item if this is a child.
     */
    public function __construct(array $innerHTML, Menu $menu, Item $parent = null)
    {
        $this->innerHTML = $innerHTML;

        parent::__construct($menu, $parent);
    }

    /**
     * Get display text of this item.
     *
     * @return array
     */
    public function getInnerHtml(): array
    {
        return $this->innerHTML;
    }
}