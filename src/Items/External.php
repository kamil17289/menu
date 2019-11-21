<?php

namespace Nethead\Menu\Items;

use Nethead\Menu\Menu;

class External extends TextItem {
    public function __construct(string $text, string $url, Menu $menu, ?Item $parent = null)
    {
        parent::__construct($text, $menu, $parent);

        $this->setHtmlAttribute('href', $url);
    }
}