<?php

namespace Nethead\Menu\Factories;

use Nethead\Menu\Items\Anchor;
use Nethead\Menu\Items\External;
use Nethead\Menu\Items\Internal;
use Nethead\Menu\Items\Separator;
use Nethead\Menu\Items\TextItem;

class ItemsFactory {
    protected $config = [];

    protected $items = [];

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function getCreatedItems() : array
    {
        return $this->items;
    }

    public function separator(array $attributes = [])
    {
        $separator = new Separator($this->config['menu'], $this->config['parent']);

        $separator->setHtmlAttributes($attributes);

        $this->items[] = $separator;

        return $separator;
    }

    public function text(string $text, array $attributes = [])
    {
        $text = new TextItem($text, $this->config['menu'], $this->config['parent']);

        $text->setHtmlAttributes($attributes);

        $this->items[] = $text;

        return $text;
    }

    public function anchor(string $text, string $anchor, array $attributes = [])
    {
        $anchor = new Anchor($text, $anchor, $this->config['menu'], $this->config['parent']);

        $anchor->setHtmlAttributes($attributes);

        $this->items[] = $anchor;

        return $anchor;
    }

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