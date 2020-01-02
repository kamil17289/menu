<?php

namespace Nethead\Menu\Activators;

use Nethead\Menu\Contracts\ActivableItem;
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
     * @var null|Closure
     * @var null
     */
    protected $customActivator = null;

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
    public function test(ActivableItem $item) : bool
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
     * Set the custom activation function to process items as you want
     * The item detected as active will be injected as a first argument
     * @param \Closure $activator
     */
    public function setCustomActivator(\Closure $activator)
    {
        $this->customActivator = $activator;
    }

    /**
     * Basic processing which is used for activating items if no custom activator was set
     * @param Item $item
     */
    protected function basicActivator(Item $item)
    {
        $item->appendToAttribute('class', '-active');

        if ($item->hasChildren()) {
            $item->appendToAttribute('class', '-expanded');
        }
    }

    /**
     * @param Item $item
     * @return mixed|void
     */
    public function activate(Item $item)
    {
        if ($item instanceof ActivableItem) {
            $item->setActive(true);
        }

        if (! is_null($this->customActivator)) {
            call_user_func($this->customActivator, $item);
        }
        else {
            $this->basicActivator($item);
        }

        if ($item->hasParent()) {
            $this->activate($item->getParent());
        }
    }
}