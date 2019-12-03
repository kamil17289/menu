<?php

namespace Nethead\Menu\Contracts;

/**
 * Interface ActivatorInterface
 * @package Nethead\Menu\Contracts
 */
interface ActivatorInterface {
    /**
     * @param ActivableItem $item
     * @return bool
     */
    public function isActive(ActivableItem $item) : bool;

    /**
     * @param ActivableItem $item
     * @return mixed
     */
    public function activate(ActivableItem $item);
}