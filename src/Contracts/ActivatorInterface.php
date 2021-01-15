<?php

namespace Nethead\Menu\Contracts;

use Nethead\Menu\Items\Item;

/**
 * Interface ActivatorInterface.
 * Defines interface for Activators. If you need to register your
 * own Activator, you should implement this interface.
 *
 * @package Nethead\Menu\Contracts
 */
interface ActivatorInterface
{
    /**
     * Test the Item against the current request URL.
     *
     * @param ActivableItem $item
     *  Item instance that will be tested, implementing the ActivableItem interface.
     * @return bool
     *  Returns TRUE if Item's URL is matching current request URL, FALSE otherwise.
     */
    public function test(ActivableItem $item): bool;

    /**
     * Activate the Item. Item should be checked first with the test() method!
     *
     * @param Item $item
     *  Item instance to activate.
     * @return void
     */
    public function activate(Item $item);
}