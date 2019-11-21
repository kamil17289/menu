<?php

namespace Nethead\Menu\Items;

use Nethead\Menu\Menu;

/**
 * Class Anchor
 * @package Nethead\Menu\Items
 */
class Anchor extends TextItem {
    /**
     * Anchor constructor.
     * @param string $text
     * @param string $anchorName
     * @param Menu $menu
     * @param Item|null $parent
     */
    public function __construct(string $text, string $anchorName, Menu $menu, Item $parent = null)
    {
        parent::__construct($text, $menu, $parent);

        $this->setHtmlAttribute('href', '#' . $anchorName);
    }

    /**
     * @return string
     */
    public function render(): string
    {
        return Menu::getRenderer()->render($this);
    }
}