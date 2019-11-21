<?php

namespace Nethead\Menu\Contracts;

interface ActivatorInterface {
    public function isActive(string $url);
}