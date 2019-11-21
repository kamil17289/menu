<?php

namespace Nethead\Menu;

use Nethead\Menu\Contracts\ActivatorInterface;
use Nethead\Menu\Contracts\RendererInterface;
use Nethead\Menu\Items\Item;

/**
 * Class Menu
 * @package Nethead\Menu
 */
class Menu implements \Countable {
    /**
     * @var string
     */
    protected $name = 'Menu';

    /**
     * @var array
     */
    protected $items = [];

    /**
     * @var null
     */
    protected static $renderer = null;

    /**
     * @var null
     */
    protected static $activator = null;

    /**
     * Menu constructor.
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getItems() : array
    {
        return $this->items;
    }

    /**
     * @param Item $item
     */
    public function setItem(Item $item)
    {
        $this->items[] = $item;
    }

    /**
     * Countable interface implementation
     * @return int
     */
    public function count() {
        return count($this->items);
    }

    /**
     * @param RendererInterface $renderer
     */
    public static function setRenderer(RendererInterface $renderer)
    {
        self::$renderer = $renderer;
    }

    /**
     * @return RendererInterface
     */
    public static function getRenderer() : RendererInterface
    {
        return self::$renderer;
    }

    /**
     * @param ActivatorInterface $activator
     */
    public static function setActivator(ActivatorInterface $activator)
    {
        self::$activator = $activator;
    }

    /**
     * @return ActivatorInterface
     */
    public static function getActivator() : ActivatorInterface
    {
        return self::$activator;
    }
}