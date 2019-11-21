<?php

namespace Nethead\Menu\Items;

use Nethead\Menu\Menu;
use Nethead\Markup\Html\HasHtmlAttributes;

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

    /**
     * @param Item $child
     */
    public function addChild(Item $child)
    {
        array_push($this->children, $child);
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