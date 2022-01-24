<?php

namespace Nethead\Menu;

use Nethead\Menu\Contracts\ActivatorInterface;
use Nethead\Menu\Contracts\RendererInterface;
use Nethead\Menu\Contracts\ActivableItem;
use Nethead\Menu\Factories\ItemsFactory;
use Nethead\Menu\Items\Item;

/**
 * Menu is a object representing navigation of your website.
 * It groups links so they can be rendered as one consistent
 * UI element.
 *
 * @package Nethead\Menu
 */
class Menu implements \Countable {
    /**
     * Name of this menu
     *
     * @var string
     */
    protected $name = 'Menu';

    /**
     * Items within this menu.
     *
     * @var array
     */
    protected $items = [];

    /**
     * A reference to an active item, if any of them was activated.
     *
     * @var null|Item
     */
    protected $activeItem = null;

    /**
     * Renderer instance.
     * You can create each menu object using different renderer.
     * This let's you change the menu markup for each menu as you wish.
     *
     * @var null|RendererInterface
     */
    protected $renderer = null;

    /**
     * Activator instance.
     * Activator decides if the item within the menu should be considered active
     * for the current URL.
     *
     * @var null|ActivatorInterface
     */
    protected $activator = null;

    /**
     * Menu constructor.
     *
     * @param string $name
     *  Name for the menu, best to use machine-readable-names
     * @param ActivatorInterface $activator
     *  Implementation of the ActivatorInterface. Activators are objects
     *  that analyze the Items and decides if the Item is active for the
     *  current request. You can use the provided Nethead\Menu\Activators\BasicUrlActivator
     *  class instance.
     * @param RendererInterface $renderer
     *  Implementation of the RendererInterface. Renderers are objects that
     *  renders the menu elements into HTML in a desired way. You can use the
     *  \Nethead\Menu\Renderers\MarkupRenderer class instance out of the box
     */
    public function __construct(string $name, ActivatorInterface $activator, RendererInterface $renderer)
    {
        $this->name = $name;
        $this->activator = $activator;
        $this->renderer = $renderer;
    }

    /**
     * Returns the name of the menu.
     *
     * @return string
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * Returns all items within this menu.
     *
     * @return array
     */
    public function getItems() : array
    {
        return $this->items;
    }

    /**
     * Add an item to this menu.
     *
     * @param Item $item
     *  Item object you want to add.
     * @return Menu
     */
    public function setItem(Item $item): Menu
    {
        $item->setMenu($this);

        $this->items[] = $item;

        return $this;
    }

    /**
     * Add multiple items with a help of a ItemsFactory.
     *
     * @param \Closure $creator
     *  Creator is a function that receives the ItemsFactory as a first parameter.
     *  You can use the ItemsFactory to create anything you need within your menu.
     *  Don't worry about returning anything from the closure, it will be automatically
     *  be returned to the menu items registry.
     * @see ItemsFactory
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
     * Render the menu as HTML.
     *
     * @return string
     */
    public function render(): string
    {
        if (is_null($this->activeItem)) {
            $this->findActiveItem();
        }

        return self::getRenderer()->render($this);
    }

    /**
     * Find item that corresponds to the current URL.
     * The item that will be marked as active depends on the method
     * used by the ActivatorInterface implementation.
     *
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
     * Countable interface implementation.
     *
     * @return int
     */
    public function count(): int
    {
        return count($this->items);
    }

    /**
     * Get the Renderer instance.
     *
     * @return RendererInterface
     */
    public function getRenderer(): RendererInterface
    {
        return $this->renderer;
    }

    /**
     * Get the Activator instance.
     *
     * @return ActivatorInterface
     */
    public function getActivator(): ActivatorInterface
    {
        return $this->activator;
    }

    /**
     * Convert the Menu into HTML string.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->render();
    }
}