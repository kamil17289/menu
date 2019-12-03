<?php

namespace Nethead\Menu\Activators;

use Nethead\Menu\Contracts\ActivableItem;
use Nethead\Menu\Contracts\ActivatorInterface;

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
        $this->currentUrl = $_SERVER['REQUEST_URI'] ?? '';

        if ($this->currentUrl === false) {
            throw new \RuntimeException('Current URL is seriously malformed!');
        }
    }

    /**
     * @param ActivableItem $item
     * @return bool
     */
    public function isActive(ActivableItem $item) : bool
    {
        $url = $item->getUrl();

        if (empty($this->currentUrl)) {
            return false;
        }

        if ($url === $this->currentUrl)
            return true;

        $parsedUrl = parse_url($url);

        $parsedCurrentUrl = parse_url($this->currentUrl);

        if (isset($parsedCurrentUrl['host']) && isset($parsedUrl['host'])) {
            if ($parsedUrl['host'] != $parsedCurrentUrl['host']) {
                return false;
            }
        }

        if ($parsedUrl['path'] != $parsedCurrentUrl['path']) {
            return false;
        }

        return true;
    }

    /**
     * @param ActivableItem $item
     * @return mixed|void
     */
    public function activate(ActivableItem $item)
    {
        
    }
}