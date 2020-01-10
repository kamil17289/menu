<?php

namespace Nethead\Menu\Factories;

use Nethead\Menu\Activators\BasicUrlActivator;
use Nethead\Menu\Contracts\ActivatorInterface;
use Nethead\Menu\Contracts\RendererInterface;
use Nethead\Menu\Menu;
use Nethead\Menu\Renderers\MarkupRenderer;
use Nethead\Menu\Repository;

/**
 * Class MenusFactory
 * @package Nethead\Menu\Factories
 */
class MenusFactory {
    /**
     * Default activators and renderers
     * @var array
     */
    protected static $defaults = [
        ActivatorInterface::class => BasicUrlActivator::class,
        RendererInterface::class => MarkupRenderer::class,
    ];

    /**
     * Create a new Menu. Using this factory will automatically make the menu
     * accessible via the Repository
     * @param string $name
     * @param ActivatorInterface|null $activator
     * @param RendererInterface|null $renderer
     * @param \Closure|null $creator
     */
    public static function make(
        string $name,
        ActivatorInterface $activator = null,
        RendererInterface $renderer = null,
        \Closure $creator = null
    ) {
        if (is_null($activator)) {
            $activatorClass = self::$defaults[ActivatorInterface::class];
            $activator = new $activatorClass();
        }

        if (is_null($renderer)) {
            $rendererClass = self::$defaults[RendererInterface::class];
            $renderer = new $rendererClass();
        }

        $menu = new Menu($name, $activator, $renderer);

        if (! is_null($creator)) {
            $menu->createItems($creator);
        }

        Repository::set($menu);
    }
}