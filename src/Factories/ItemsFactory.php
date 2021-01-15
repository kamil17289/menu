<?php

namespace Nethead\Menu\Factories;

use Nethead\Menu\Items\Anchor;
use Nethead\Menu\Items\External;
use Nethead\Menu\Items\Internal;
use Nethead\Menu\Items\Item;
use Nethead\Menu\Items\Separator;
use Nethead\Menu\Items\SimpleItem;
use Nethead\Menu\Items\Special;

/**
 * ItemsFactory is a helper for quick creating whole sets of items.
 *
 * @see \Nethead\Menu\Menu::createItems()
 * @package Nethead\Menu\Factories
 */
class ItemsFactory {
    /**
     * Factory configuration.
     *
     * @var array
     */
    protected $config = [];

    /**
     * List of items created by this factory.
     *
     * @var array
     */
    protected $items = [];

    /**
     * Custom factory methods.
     * Array of your own factory methods. You can add this by extending the ItemsFactory,
     * or on runtime using the addFactory methods.
     *
     * @see ItemsFactory::addFactory()
     * @var array
     */
    protected static $methods = [];

    /**
     * ItemsFactory constructor.
     *
     * @param array $config
     *  Factory configuration, holds menu and parent objects references that
     *  will be applied to every item created with this factory instance.
     *  It must contain at least two keys: 'menu' and 'parent'.
     *   - 'menu' is a reference to the Menu object that will hold the items created with this factory
     *   - 'parent' is the reference to a parent item, if the factory is used for creating the sub-menu, null otherwise.
     *   - 'template' (optional) (string) sets the template for all items created with this factory
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * Add factory methods for creating your own types of items.
     *
     * @param string $name
     *  Short name of the factory you are registering.
     * @param callable $factory
     *  The factory method
     */
    public static function addFactory(string $name, callable $factory)
    {
        self::$methods[$name] = $factory;
    }

    /**
     * Returns all items created by this factory.
     *
     * @return array
     */
    public function getCreatedItems() : array
    {
        return $this->items;
    }

    /**
     * Process created item before returning it.
     *
     * @param Item $item
     * @return Item
     */
    protected function processBeforeReturn(Item $item): Item
    {
        if (isset($this->config['template']) && ! empty($this->config['template'])) {
            $item->setTemplate($this->config['template']);
        }

        return $item;
    }

    /**
     * Create a separator menu item.
     * Separators doesn't have any function, they are just for decoration.
     *
     * @return Separator
     */
    public function separator(): Separator
    {
        $separator = new Separator($this->config['menu'], $this->config['parent']);

        $this->items[] = $separator;

        $this->processBeforeReturn($separator);

        return $separator;
    }

    /**
     * @param array $innerHTML
     * @return SimpleItem
     */
    public function simple(array $innerHTML): SimpleItem
    {
        $simpleItem = new SimpleItem($innerHTML, $this->config['menu'], $this->config['parent']);

        $this->items[] = $simpleItem;

        $this->processBeforeReturn($simpleItem);

        return $simpleItem;
    }

    /**
     * @param array $innerHTML
     * @param string $anchorID
     * @return Anchor
     */
    public function anchor(array $innerHTML, string $anchorID): Anchor
    {
        $anchor = new Anchor($innerHTML, $anchorID, $this->config['menu'], $this->config['parent']);

        $this->items[] = $anchor;

        $this->processBeforeReturn($anchor);

        return $anchor;
    }

    /**
     * Create a special destination link, like mailto: or tel:
     * 
     * @param array $innerHTML
     * @param string $scheme
     * @param string $href
     * @return Special
     */
    public function special(array $innerHTML, string $scheme, string $href): Special
    {
        $link = new Special($innerHTML, $scheme, $href, $this->config['menu'], $this->config['parent']);

        $this->items[] = $link;

        $this->processBeforeReturn($link);

        return $link;
    }

    /**
     * @param array $innerHTML
     * @param string $url
     * @return External
     */
    public function external(array $innerHTML, string $url): External
    {
        $link = new External($innerHTML, $url, $this->config['menu'], $this->config['parent']);

        $this->items[] = $link;

        $this->processBeforeReturn($link);

        return $link;
    }

    /**
     * @param array $innerHTML
     * @param $url
     * @return Internal
     */
    public function internal(array $innerHTML, $url): Internal
    {
        $link = new Internal($innerHTML, $url, $this->config['menu'], $this->config['parent']);

        $this->items[] = $link;

        $this->processBeforeReturn($link);

        return $link;
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