<?php

namespace Nethead\Menu\Items;

use Nethead\Menu\Menu;

/**
 * Class Internal
 * @package Nethead\Menu\Items
 */
class Internal extends External {
    /**
     * @var bool
     */
    protected $active = false;

    /**
     * Internal constructor.
     * @param string $text
     * @param string $url
     * @param Menu $menu
     * @param Item|null $parent
     */
    public function __construct(string $text, string $url, Menu $menu, Item $parent = null)
    {
        parent::__construct($text, $url, $menu, $parent);

        $this->checkIfActive();
    }

    /**
     * Mark the item as active
     */
    public function activate()
    {
        $this->active = true;
    }

    /**
     * Use the registered activator to check if the current item is active one
     * Then instruct the menu to activate all parent items in the tree
     */
    public function checkIfActive()
    {
        if (Menu::getActivator()->isActive($this->getUrl())) {
            $this->activate();
        }

        if ($this->isActive() && $this->hasParent()) {
            $this->activateTree();
        }
    }

    /**
     * Go up in the items tree and activate all parents
     */
    protected function activateTree()
    {
        $item = $this;

        while($parent = $item->getParent()) {
            if (method_exists($parent, 'activate')) {
                $parent->activate();
            }

            $item = $parent;
        }
    }

    /**
     * @return bool
     */
    public function isActive() : bool
    {
        return $this->active;
    }
}