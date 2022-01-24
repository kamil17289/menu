<?php

namespace Nethead\Menu\Factories;

use Nethead\Menu\Contracts\ItemInterface;
use Nethead\Menu\Items\SimpleItem;
use Nethead\Menu\Items\Separator;
use Nethead\Menu\Items\External;
use Nethead\Menu\Items\Internal;
use Nethead\Menu\Items\Special;
use Nethead\Menu\Items\Anchor;

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
     * or on runtime using the addFactory method.
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
     * Process created item before returning it and save it to the collection.
     *
     * @param ItemInterface $item
     * @return ItemInterface
     */
    protected function storeItem(ItemInterface $item): ItemInterface
    {
        if (isset($this->config['template']) && ! empty($this->config['template'])) {
            $item->setTemplate($this->config['template']);
        }

        $this->items[] = $item;

        return $item;
    }

    /**
     * Create a separator menu item.
     * Separators have no function, they are just for decoration.
     *
     * @return ItemInterface
     */
    public function separator(): ItemInterface
    {
        return $this->storeItem(
            new Separator($this->config['menu'], $this->config['parent'])
        );
    }

    /**
     * @param array $innerHTML
     * @return ItemInterface
     */
    public function simple(array $innerHTML): ItemInterface
    {
        return $this->storeItem(
            new SimpleItem($innerHTML, $this->config['menu'], $this->config['parent'])
        );
    }

    /**
     * @param array $innerHTML
     * @param string $anchorID
     * @return ItemInterface
     */
    public function anchor(array $innerHTML, string $anchorID): ItemInterface
    {
        return $this->storeItem(
            new Anchor($innerHTML, $anchorID, $this->config['menu'], $this->config['parent'])
        );
    }

    /**
     * Create a special destination link, like mailto: or tel:
     * 
     * @param array $innerHTML
     * @param string $scheme
     * @param string $href
     * @return ItemInterface
     */
    public function special(array $innerHTML, string $scheme, string $href): ItemInterface
    {
        return $this->storeItem(
            new Special($innerHTML, $scheme, $href, $this->config['menu'], $this->config['parent'])
        );
    }

    /**
     * @param array $innerHTML
     * @param string $url
     * @return ItemInterface
     */
    public function external(array $innerHTML, string $url): ItemInterface
    {
        return $this->storeItem(
            new External($innerHTML, $url, $this->config['menu'], $this->config['parent'])
        );
    }

    /**
     * @param array $innerHTML
     * @param $url
     * @return ItemInterface
     */
    public function internal(array $innerHTML, $url): ItemInterface
    {
        return $this->storeItem(
            new Internal($innerHTML, $url, $this->config['menu'], $this->config['parent'])
        );
    }

    /**
     * Call a custom factory method using it's name
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        if (array_key_exists($name, self::$methods)) {
            $arguments['config'] = $this->config;

            return $this->storeItem(
                call_user_func_array(self::$methods[$name], $arguments)
            );
        }

        throw new \RuntimeException('Factory method ' . $name . ' not found!');
    }
}