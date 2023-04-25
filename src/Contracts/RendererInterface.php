<?php

namespace Nethead\Menu\Contracts;

/**
 * RendererInterface defines the interface for Renderers.
 * Implement it in your renderer to make sure you can render your
 * menu Items your way.
 *
 * @package Nethead\Menu\Renderers
 */
interface RendererInterface {
    /**
     * Main render method. It is the only one needed,
     * will be called for every type of Item.
     *
     * @param object $item
     *  Item that will be rendered to HTML.
     * @return mixed
     *  HTML string
     */
    public function render(object $item);
}
