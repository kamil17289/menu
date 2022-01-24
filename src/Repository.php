<?php

namespace Nethead\Menu;

/**
 * Repository is a central storage for all menus you define.
 * Read through the methods documentation to check how to do add, retrieve and render your menus.
 *
 * @package Nethead\Menu
 */
final class Repository implements \ArrayAccess {
    /**
     * Menus array stores all your defined menus.
     *
     * @var array
     */
    protected static $menus = [];

    /**
     * Get the menu by its name.
     *
     * @param string $name
     *  Name of the menu you specified when added to a repository.
     * @return Menu|null
     *  Menu object if the menu is registered, or null if it's not.
     */
    public static function get(string $name): ?Menu
    {
        if (array_key_exists($name, self::$menus)) {
            return self::$menus[$name];
        }

        return null;
    }

    /**
     * Register new menu in the repository.
     *
     * @param Menu $menu
     *  The Menu object representing your menu.
     * @param string $name
     *  The name for the menu. This name is later used for retrieving the menu
     *  with 'get' method. If empty, Repository will use the name from the Menu object.
     * @throws \RuntimeException
     *  Throws \RuntimeException if the name has already been used
     */
    public static function set(Menu $menu, string $name = '')
    {
        if (empty($name)) {
            $name = $menu->getName();
        }

        if (array_key_exists($name, self::$menus)) {
            throw new \RuntimeException('Trying to overwrite the menu with a name ' . $name);
        }

        self::$menus[$name] = $menu;
    }

    /**
     * Render entire menu of a given name to an HTML string.
     *
     * @param $name
     *  Name of the registered menu that will be rendered.
     * @return string
     *  The Menu object rendered into string
     *  or empty string if the given menu is not registered.
     */
    public function render($name): string
    {
        if ($menu = self::get($name)) {
            return $menu->render();
        }

        return '';
    }

    /**
     * ArrayAccess implementation.
     *
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset) : bool
    {
        return array_key_exists($offset, self::$menus);
    }

    /**
     * ArrayAccess implementation.
     *
     * @param mixed $offset
     * @return mixed|null
     */
    public function offsetGet($offset)
    {
        if (array_key_exists($offset, self::$menus)) {
            return self::$menus[$offset];
        }

        return null;
    }

    /**
     * ArrayAccess implementation.
     *
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        self::set($value, $offset);
    }

    /**
     * ArrayAccess implementation.
     *
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        if (array_key_exists($offset, self::$menus)) {
            unset(self::$menus[$offset]);
        }
    }
}