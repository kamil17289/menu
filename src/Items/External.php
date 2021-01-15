<?php

namespace Nethead\Menu\Items;

use Nethead\Menu\Menu;

/**
 * External Item is creating a link which goes outside of your website.
 *
 * @package Nethead\Menu\Items
 */
class External extends SimpleItem {
    /**
     * @var string
     */
    protected $url = '/';

    /**
     * External constructor.
     *
     * @param array $innerHTML
     *  Everything you want to display inside the Item's HTML.
     * @param Menu $menu
     *  Menu instance that this item will be bind to.
     * @param Item|null $parent
     *  Parent Item if this is a child.
     * @param string $url
     *  URL you want to link to with this item.
     */
    public function __construct(array $innerHTML, string $url, Menu $menu, Item $parent = null)
    {
        parent::__construct($innerHTML, $menu, $parent);

        $this->url = $url;
    }

    /**
     * Get the URL.
     *
     * @return string
     */
    public function getUrl() : string
    {
        return $this->url;
    }
}