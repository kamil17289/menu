<?php

namespace Nethead\Menu\Items;

use Nethead\Menu\Commons\IconDecorator;
use Nethead\Menu\Menu;

/**
 * Class TextItem
 * @package Nethead\Menu\Items
 */
class TextItem extends Item {
    use IconDecorator;

    /**
     * @var string
     */
    protected $displayText = '';

    /**
     * TextItem constructor.
     * @param string $text
     * @param Menu $menu
     * @param Item|null $parent
     */
    public function __construct(string $text, Menu $menu, Item $parent = null)
    {
        $this->displayText = $text;

        parent::__construct($menu, $parent);
    }

    /**
     * Get display text of this item
     * @return string
     */
    public function getDisplayText()
    {
        return $this->displayText;
    }
}