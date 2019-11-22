<?php

namespace Nethead\Menu\Contracts;

/**
 * Interface RendererInterface
 * @package Nethead\Menu\Renderers
 */
interface RendererInterface {
    public function render(object $item) : string;
}