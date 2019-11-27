<?php

namespace Nethead\Menu\Contracts;

interface ActivableItem {
    public function getUrl() : string;
    public function hasParent() : bool;
    public function getChildren() : array;
    public function activate() : void;
    public function activateTree() : void;

}