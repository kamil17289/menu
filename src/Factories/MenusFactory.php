<?php

namespace Nethead\Menu\Factories;

use Nethead\Menu\Activators\BasicUrlActivator;
use Nethead\Menu\Contracts\ActivatorInterface;
use Nethead\Menu\Contracts\RendererInterface;
use Nethead\Menu\Renderers\MarkupRenderer;
use Nethead\Menu\Repository;
use Nethead\Menu\Menu;

/**
 * MenusFactory is a helper for easy creating the whole Menus.
 *
 * @package Nethead\Menu\Factories
 */
class MenusFactory {
    /**
     * Default activators and renderers used by the factory to create menus.
     *
     * @var array
     */
    protected static $defaults = [
        ActivatorInterface::class => BasicUrlActivator::class,
        RendererInterface::class => MarkupRenderer::class,
    ];

    /**
     * Create a new Menu. Using this factory will automatically make the menu
     * accessible via the Repository static methods.
     *
     * @param string $name
     *  Name for the Menu, for example "main-menu"
     * @param ActivatorInterface|null $activator
     *  Activataor that will be used to mark items as active, if they are ActivableItem
     *  and if the URL test returns true
     * @param RendererInterface|null $renderer
     *  Renderer instance that will be used for rendering the object representation of the menu
     *  to a HTML string that can be printed in the template.
     * @param \Closure|null $creator
     *  Anonymous function that will receive ItemsFactory as a first parameter.
     *  Use it to quickly add items to your new menu.
     */
    public static function make(
        string $name,
        \Closure $creator = null,
        ActivatorInterface $activator = null,
        RendererInterface $renderer = null
    ) {
        if (is_null($activator)) {
            $activatorClass = static::getDefaultActivator();
            $activator = new $activatorClass();
        }

        if (is_null($renderer)) {
            $rendererClass = static::getDefaultRenderer();
            $renderer = new $rendererClass();
        }

        $menu = new Menu($name, $activator, $renderer);

        if (! is_null($creator)) {
            $menu->createItems($creator);
        }

        Repository::set($menu);
    }

    /**
     * @return string
     */
    protected static function getDefaultActivator(): string
    {
        return static::$defaults[ActivatorInterface::class];
    }

    /**
     * @return string
     */
    protected static function getDefaultRenderer(): string
    {
        return static::$defaults[RendererInterface::class];
    }
}