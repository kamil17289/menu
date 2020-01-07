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
    protected $renderer = null;

    /**
     * Activator instance
     * @var null
     */
    protected $activator = null;

    /**
     * Menu constructor.
     * @param string $name
     * @param ActivatorInterface $activator
     * @param RendererInterface $renderer
     */
    public function __construct(string $name, ActivatorInterface $activator, RendererInterface $renderer)
    {
        $this->name = $name;
        $this->activator = $activator;
        $this->renderer = $renderer;
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
     * @param array $items
     */
    public function findActiveItem(array $items = [])
    {
        if (empty($items)) {
            $items = $this->items;
        }

        foreach ($items as $item) {
            if ($item instanceof ActivableItem) {
                if (self::getActivator()->test($item)) {
                    self::getActivator()->activate($item);
                    $this->activeItem = $item;
                    return;
                }
            }

            if ($item->hasChildren()) {
                $this->findActiveItem($item->getChildren());
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
     * @return RendererInterface
     */
    public function getRenderer() : RendererInterface
    {
        return $this->renderer;
    }

    /**
     * @return ActivatorInterface
     */
    public function getActivator() : ActivatorInterface
    {
        return $this->activator;
    }
}