<?php

namespace Nethead\Menu\Contracts;

/**
 * Interface ActivableItem.
 * Defined the interface for Items that can be activated in the menu.
 *
 * @package Nethead\Menu\Contracts
 */
interface ActivableItem {
    /**
     * Method for setting the Item as active.
     *
     * @param bool $status
     *  Boolean value, TRUE if the Item should be activated, FALSE otherwise.
     * @return void
     */
    public function setActive(bool $status);

    /**
     * Method for checking the Item was set as active.
     * Should return boolean value.
     *
     * @return bool
     *  TRUE if the item is active, FALSE otherwise.
     */
    public function isActive() : bool;

    /**
     * Check if the Item has a parent Item.
     *
     * @return bool
     *  Should return TRUE if the Item has a parent Item, FALSE otherwise.
     */
    public function hasParent() : bool;

    /**
     * Check if the Item has any child elements.
     *
     * @return bool
     *  Should return TRUE if the item has child items, FALSE otherwise.
     */
    public function hasChildren() : bool;

    /**
     * Get the URL of the item, it is needed for testing.
     *
     * @return string
     *  URL of the tested Item instance.
     */
    public function getUrl() : string;
}