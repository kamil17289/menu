<?php

namespace Nethead\Menu\Items;

use Nethead\Markup\Html\HasHtmlAttributes;
use Nethead\Menu\Factories\ItemsFactory;
use Nethead\Menu\Menu;

/**
 * Class Item
 * @package Nethead\Menu\Items
 */
abstract class Item {
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
    protected $template = '';

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

        $items = call_user_func($creator, new ItemsFactory($config));

        if (! is_array($items)) {
            throw new \RuntimeException('Grouping callback should return array!');
        }

        $this->children += $items;
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
    public function hasParent() {
        return ! is_null($this->parent);
    }

    /**
     * @return Item|null
     */
    public function getParent() {
        return $this->parent;
    }

    /**
     * @param string $template
     */
    public function setTemplate(string $template)
    {
        $this->template = $template;
    }

    /**
     * @return string
     */
    abstract public function render() : string;
}