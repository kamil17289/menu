<?php

namespace Nethead\Menu\Renderers;

use Nethead\Menu\Contracts\RendererInterface;
use Nethead\Menu\Items\Item;

/**
 * Class ObRenderer
 * @package Nethead\Menu\Renderers
 */
class ObRenderer implements RendererInterface {
    public function render(Item $item) : string
    {
        dd(get_class($item));
    }
}