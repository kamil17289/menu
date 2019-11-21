<?php

namespace Nethead\Menu\Contracts;

use Nethead\Menu\Items\Item;

/**
 * Interface RendererInterface
 * @package Nethead\Menu\Renderers
 */
interface RendererInterface {
    public function render(Item $item) : string;
}