<?php

namespace Nethead\Menu\Items;

use Nethead\Menu\Menu;

/**
 * Class External
 * @package Nethead\Menu\Items
 */
class External extends TextItem {
    /**
     * @var string
     */
    protected $url = '/';

    /**
     * External constructor.
     * @param string $text
     * @param string $url
     * @param Menu $menu
     * @param Item|null $parent
     */
    public function __construct(string $text, string $url, Menu $menu, Item $parent = null)
    {
        parent::__construct($text, $menu, $parent);

        $this->url = $url;

        $this->setHtmlAttribute('href', $url);
    }

    /**
     * @return string
     */
    public function getUrl() : string
    {
        return $this->url;
    }
}