<?php

namespace Nethead\Menu\Items;

use Nethead\Menu\Contracts\ActivableItem;
use Nethead\Menu\Menu;

/**
 * Internal Item is pretty much the same as Extenal. The only difference
 * is that it can be activated.
 *
 * @package Nethead\Menu\Items
 */
class Internal extends External implements ActivableItem {
    /**
     * Indicates if the Item has been activated.
     *
     * @var bool
     */
    protected $active = false;

    /**
     * Internal constructor.
     *
     * @param array $innerHTML
     *  Everything you want to display inside the Item's HTML.
     * @param Menu $menu
     *  Menu instance that this item will be bind to.
     * @param Item|null $parent
     *  Parent Item if this is a child.
     * @param string $url
     *  Internal URL for this item.
     */
    public function __construct(array $innerHTML, string $url, Menu $menu, Item $parent = null)
    {
        parent::__construct($innerHTML, $url, $menu, $parent);
    }

    /**
     * Mark the item as active.
     *
     * @param bool $status
     */
    public function setActive(bool $status = true)
    {
        $this->active = $status;
    }

    /**
     * Check if this item was activated.
     *
     * @return bool
     */
    public function isActive() : bool
    {
        return $this->active;
    }
}