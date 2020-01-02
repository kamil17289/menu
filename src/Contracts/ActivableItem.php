<?php

namespace Nethead\Menu\Contracts;

/**
 * Interface ActivableItem
 * @package Nethead\Menu\Contracts
 */
interface ActivableItem {
    public function setActive(bool $status);
    public function isActive() : bool;
    public function hasParent() : bool;
    public function hasChildren() : bool;
    public function getUrl() : string;
}