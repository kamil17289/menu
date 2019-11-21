<?php

namespace Nethead\Menu\Factories;

use Nethead\Menu\Items\Anchor;
use Nethead\Menu\Items\External;
use Nethead\Menu\Items\Internal;
use Nethead\Menu\Items\Separator;
use Nethead\Menu\Items\TextItem;

/**
 * Class ItemsFactory
 * @package Nethead\Menu\Factories
 */
class ItemsFactory {
    /**
     * @var array
     */
    protected $config = [];

    /**
     * @var array
     */
    protected $items = [];

    /**
     * ItemsFactory constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @return array
     */
    public function getCreatedItems() : array
    {
        return $this->items;
    }

    /**
     * @param array $attributes
     * @return Separator
     */
    public function separator(array $attributes = [])
    {
        $separator = new Separator($this->config['menu'], $this->config['parent']);

        $separator->setHtmlAttributes($attributes);

        $this->items[] = $separator;

        return $separator;
    }

    /**
     * @param string $text
     * @param array $attributes
     * @return TextItem|string
     */
    public function text(string $text, array $attributes = [])
    {
        $text = new TextItem($text, $this->config['menu'], $this->config['parent']);

        $text->setHtmlAttributes($attributes);

        $this->items[] = $text;

        return $text;
    }

    /**
     * @param string $text
     * @param string $anchor
     * @param array $attributes
     * @return Anchor|string
     */
    public function anchor(string $text, string $anchor, array $attributes = [])
    {
        $anchor = new Anchor($text, $anchor, $this->config['menu'], $this->config['parent']);

        $anchor->setHtmlAttributes($attributes);

        $this->items[] = $anchor;

        return $anchor;
    }

    /**
     * @param string $text
     * @param string $url
     * @param array $attributes
     * @param bool $external
     * @return External|Internal
     */
    public function link(string $text, string $url, array $attributes = [], $external = false)
    {
        if ($external) {
            $link = new External($text, $url, $this->config['menu'], $this->config['parent']);
        }
        else {
            $link = new Internal($text, $url, $this->config['menu'], $this->config['parent']);
        }

        $link->setHtmlAttributes($attributes);

        $this->items[] = $link;

        return $link;
    }
}