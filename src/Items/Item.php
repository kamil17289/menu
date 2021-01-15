<?php

namespace Nethead\Menu\Items;

use Nethead\Menu\Factories\ItemsFactory;
use Nethead\Menu\Menu;

/**
 * The Item class represents every menu item which can be registered inside the Menu.
 * Item is a base class which only binds itself to a Menu in which it is registered,
 * and also the parent item if you are dealing with the multilevel menus.
 * It's an abstract construct that can't be used as a functional menu item, but gathers
 * all basic functionality needed for other item types to work.
 *
 * @package Nethead\Menu\Items
 */
abstract class Item {
    /**
     * The Menu that this Item is bind to. Initially null, but
     * basically the item must be bind to a menu.
     *
     * @var null|Menu
     */
    protected $menu = null;

    /**
     * Parent menu item of this item, or null if this is not a child item.
     *
     * @var Item|null
     */
    protected $parent = null;

    /**
     * Child items of this Item, if any.
     *
     * @var array
     */
    protected $children = [];

    /**
     * Template for this item, if set by the ItemsFactory.
     * Can be useful if you are using renderer not based on the Nethead\Markup package.
     *
     * @see \Nethead\Menu\Factories\ItemsFactory::processBeforeReturn()
     * @var string
     */
    protected $template = null;

    /**
     * Item constructor.
     *
     * @param Menu $menu
     *  Menu object this item is bind to.
     * @param Item|null $parent
     *  Parent Item if this is a child item. Null if not.
     */
    public function __construct(Menu $menu, Item $parent = null)
    {
        $this->menu = $menu;
        $this->parent = $parent;
    }

    /**
     * Set the menu which this Item belongs to (bind).
     *
     * @param Menu $menu
     *  Menu object
     */
    public function setMenu(Menu $menu)
    {
        $this->menu = $menu;
    }

    /**
     * Add a child item.
     *
     * @param Item $child
     *  Item instance which will be rendered as a child of this item.
     */
    public function addChild(Item $child)
    {
        array_push($this->children, $child);
    }

    /**
     * Add child items with help of ItemsFactory.
     *
     * @param \Closure $creator
     *  Function that will receive ItemsFactory as a first parameter.
     *  You can use it to quickly add many child items to this Item.
     * @param array $config
     *  Config array for the ItemsFactory
     * @see ItemsFactory::__construct()
     *  For information on the $config array
     */
    public function group(\Closure $creator, array $config = [])
    {
        $config['parent'] = $this;
        $config['menu'] = $this->menu;

        $factory = new ItemsFactory($config);

        call_user_func($creator, $factory);

        $this->children = array_merge($this->children, $factory->getCreatedItems());
    }

    /**
     * Check if this Item has any child items.
     *
     * @return bool
     *  TRUE if at least one child Item has been added, FALSE otherwise.
     */
    public function hasChildren() : bool
    {
        return ! empty($this->children);
    }

    /**
     * Get the array of the registered child items.
     *
     * @return array
     */
    public function getChildren() : array
    {
        return $this->children;
    }

    /**
     * Check if the Item has a parent Item.
     *
     * @return bool
     *  TRUE if this is a child Item, FALSE otherwise.
     */
    public function hasParent() : bool
    {
        return ! is_null($this->parent);
    }

    /**
     * Get the parent of this Item.
     *
     * @return Item|null
     *  Parent Item instance if it exists, NULL if it doesn't exists.
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set the template for this Item.
     *
     * @param string $template
     *  Template string
     * @return self
     */
    public function setTemplate(string $template)
    {
        $this->template = $template;

        return $this;
    }

    /**
     * Get the template of this Item.
     *
     * @return string
     *  The template string.
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * Convert this Item to HTML string.
     *
     * @return string
     */
    public function render() : string
    {
        return $this->menu->getRenderer()->render($this);
    }
}