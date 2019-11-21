<?php

namespace Nethead\Menu\Contracts;

/**
 * Interface ActivatorInterface
 * @package Nethead\Menu\Contracts
 */
interface ActivatorInterface {
    /**
     * @param string $url
     * @return bool
     */
    public function isActive(string $url) : bool;
}