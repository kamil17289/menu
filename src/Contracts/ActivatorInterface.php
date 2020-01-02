<?php

namespace Nethead\Menu\Contracts;

use Nethead\Menu\Items\Item;

/**
 * Interface ActivatorInterface
 * @package Nethead\Menu\Contracts
 */
interface ActivatorInterface
{
    /**
     * @param ActivableItem $item
     * @return bool
     */
    public function test(ActivableItem $item): bool;

    /**
     * @param Item $item
     * @return mixed
     */
    public function activate(Item $item);
}