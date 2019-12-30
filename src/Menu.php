<?php

namespace Nethead\Menu;

use Nethead\Menu\Contracts\ActivableItem;
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
     * Name of this menu
     * @var string
     */
    protected $name = 'Menu';

    /**
     * Items within this menu
     * @var array
     */
    protected $items = [];

    /**
     * Item that was found active
     * @var null|Item
     */
    protected $activeItem = null;

    /**
     * Renderer instance
     * @var null
     */
    protected static $renderer = null;

    /**
     * Activator instance
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

    /**
     * Render the menu as HTML
     * @return string
     */
    public function render()
    {
        if (is_null($this->activeItem)) {
            $this->findActiveItem();
        }

        return self::getRenderer()->render($this);
    }

    /**
     * Find item that corresponds to current URL
     */
    public function findActiveItem()
    {
        $activator = self::getActivator();

        $found = false;

        foreach ($this->items as $item) {
            if ($item instanceof ActivableItem) {
                if ($activator->isActive($item)) {
                    $activator->activate($item);
                    return;
                }
            }
        }
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