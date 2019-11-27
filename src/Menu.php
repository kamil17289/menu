<?php

namespace Nethead\Menu;

use Nethead\Menu\Contracts\ActivatorInterface;
use Nethead\Menu\Contracts\RendererInterface;
use Nethead\Menu\Factories\ItemsFactory;
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
     * @param string $name
     * @param \Closure|null $creator
     * @return Menu
     */
    public static function make(string $name, \Closure $creator = null)
    {
        $menu = new self($name);

        $menu->createItems($creator);

        return $menu;
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
     * @return Menu
     */
    public function setItem(Item $item)
    {
        $item->setMenu($this);

        $this->items[] = $item;

        return $this;
    }

    /**
     * @param \Closure $creator
     */
    public function createItems(\Closure $creator)
    {
        $factory = new ItemsFactory([
            'menu' => $this,
            'parent' => null
        ]);

        call_user_func($creator, $factory);

        $this->items = array_merge($this->items, $factory->getCreatedItems());
    }

    public function render()
    {
        return self::getRenderer()->render($this);
    }

    /**
     * Countable interface implementation
     * @return int
     */
    public function count() : int
    {
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