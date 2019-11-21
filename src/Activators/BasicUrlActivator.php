<?php

namespace Nethead\Menu\Activators;

use Nethead\Menu\Contracts\ActivatorInterface;
use Nethead\Menu\Items\Item;

/**
 * Class BasicUrlActivator
 * @package Nethead\Menu\Activators
 */
class BasicUrlActivator implements ActivatorInterface {
    /**
     * @var mixed
     */
    protected $currentUrl;

    /**
     * BasicUrlActivator constructor.
     */
    public function __construct()
    {
        $this->currentUrl = $_SERVER['REQUEST_URI'];

        if ($this->currentUrl === false) {
            throw new \RuntimeException('Current URL is seriously malformed!');
        }
    }

    /**
     * @param string $url
     * @return bool
     */
    public function isActive($url) : bool
    {
        if ($url === $this->currentUrl)
            return true;

        $parsedUrl = parse_url($url);

        $parsedCurrentUrl = parse_url($this->currentUrl);

        if ($parsedUrl['host'] != $parsedCurrentUrl['host']) {
            return false;
        }

        if ($parsedUrl['path'] != $parsedCurrentUrl['path']) {
            return false;
        }

        return true;
    }
}