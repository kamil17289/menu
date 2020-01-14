<?php

namespace Nethead\Menu\Factories;

use Nethead\Menu\Items\Anchor;
use Nethead\Menu\Items\External;
use Nethead\Menu\Items\Internal;
use Nethead\Menu\Items\Item;
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
     * Custom factory methods
     * @var array
     */
    protected static $methods = [];

    /**
     * ItemsFactory constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * Add factory methods for creating your own types of items
     * @param string $name
     * @param callable $factory
     */
    public static function addFactory(string $name, callable $factory)
    {
        self::$methods[$name] = $factory;
    }

    /**
     * @return array
     */
    public function getCreatedItems() : array
    {
        return $this->items;
    }

    /**
     * Process created item before returning it
     * @param Item $item
     * @return Item
     */
    protected function processBeforeReturn(Item $item)
    {
        if (isset($this->config['template']) && ! empty($this->config['template'])) {
            $item->setTemplate($this->config['template']);
        }

        if (method_exists($item, 'setIcon')) {
            if (isset($this->config['icon']['left'])) {
                $item->setIcon($this->config['icon']['left']);
            }

            if (isset($this->config['icon']['right'])) {
                $item->setIcon($this->config['icon']['right'], 'right');
            }
        }

        return $item;
    }

    /**
     * @param array $attributes
     * @return Separator
     */
    public function separator(array $attributes = [])
    {
        $separator = new Separator($this->config['menu'], $this->config['parent']);

        $separator->mergeHtmlAttributes($attributes);

        $this->items[] = $separator;

        return $this->processBeforeReturn($separator);
    }

    /**
     * @param string $text
     * @param array $attributes
     * @return TextItem|string
     */
    public function text(string $text, array $attributes = [])
    {
        $text = new TextItem($text, $this->config['menu'], $this->config['parent']);

        $text->mergeHtmlAttributes($attributes);

        $this->items[] = $text;

        return $this->processBeforeReturn($text);
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

        $anchor->mergeHtmlAttributes($attributes);

        $this->items[] = $anchor;

        return $this->processBeforeReturn($anchor);
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

        $link->mergeHtmlAttributes($attributes);

        $this->items[] = $link;

        return $this->processBeforeReturn($link);
    }

    /**
     * Call a custom factory method using it's name
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        if (in_array($name, self::$methods)) {
            $arguments['config'] = $this->config;

            return call_user_func_array(self::$methods[$name], $arguments);
        }

        throw new \RuntimeException('Factory method ' . $name . ' not found!');
    }
}