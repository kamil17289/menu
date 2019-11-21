<?php

namespace Nethead\Menu\Activators;

use Nethead\Menu\Contracts\ActivatorInterface;

class BasicUrlActivator implements ActivatorInterface {
    protected $currentUrl;

    public function __construct()
    {
        $this->currentUrl = $_SERVER['REQUEST_URI'];
    }

    public function isActive($url)
    {
        // TODO: Implement activate() method.
    }
}