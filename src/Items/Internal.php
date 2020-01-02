<?php

namespace Nethead\Menu\Items;

use Nethead\Menu\Contracts\ActivableItem;
use Nethead\Menu\Menu;

/**
 * Class Internal
 * @package Nethead\Menu\Items
 */
class Internal extends External implements ActivableItem {
    /**
     * @var bool
     */
    protected $active = false;

    /**
     * Internal constructor.
     * @param string $text
     * @param string $url
     * @param Menu $menu
     * @param Item|null $parent
     */
    public function __construct(string $text, string $url, Menu $menu, Item $parent = null)
    {
        parent::__construct($text, $url, $menu, $parent);
    }

    /**
     * Mark the item as active
     * @param bool $status
     */
    public function setActive(bool $status = true)
    {
        $this->active = $status;
    }

    /**
     * Check if this item was recognized as active
     * @return bool
     */
    public function isActive() : bool
    {
        return $this->active;
    }
}