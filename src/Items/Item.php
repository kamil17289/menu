<?php

namespace Nethead\Menu\Items;

use Nethead\Markup\Html\HasHtmlAttributes;
use Nethead\Menu\Factories\ItemsFactory;
use Nethead\Menu\Menu;

/**
 * Class Item
 * @package Nethead\Menu\Items
 */
class Item {
    use HasHtmlAttributes;

    /**
     * @var null|Menu
     */
    protected $menu = null;

    /**
     * @var Item|null
     */
    protected $parent = null;

    /**
     * @var array
     */
    protected $children = [];

    /**
     * @var string
     */
    protected $template = null;

    /**
     * Item constructor.
     * @param Menu $menu
     * @param Item|null $parent
     */
    public function __construct(Menu $menu, Item $parent = null)
    {
        $this->menu = $menu;
        $this->parent = $parent;
    }

    /**
     * Set the menu which this Item belongs to
     * @param Menu $menu
     */
    public function setMenu(Menu $menu)
    {
        $this->menu = $menu;
    }

    /**
     * @param Item $child
     */
    public function addChild(Item $child)
    {
        array_push($this->children, $child);
    }

    /**
     * @param \Closure $creator
     * @param array $config
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
     * @return bool
     */
    public function hasChildren() : bool
    {
        return ! empty($this->children);
    }

    /**
     * @return array
     */
    public function getChildren() : array
    {
        return $this->children;
    }

    /**
     * @return bool
     */
    public function hasParent() : bool
    {
        return ! is_null($this->parent);
    }

    /**
     * @return Item|null
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param string $template
     * @return self
     */
    public function setTemplate(string $template)
    {
        $this->template = $template;

        return $this;
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @return string
     */
    public function render() : string
    {
        return $this->menu->getRenderer()->render($this);
    }
}