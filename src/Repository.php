<?php

namespace Nethead\Menu;

/**
 * Class Repository
 * @package Nethead\Menu
 */
final class Repository implements \ArrayAccess {
    /**
     * @var array
     */
    protected static $menus = [];

    /**
     * @param string $name
     * @return Menu|null
     */
    public static function get(string $name)
    {
        if (array_key_exists($name, self::$menus)) {
            return self::$menus[$name];
        }

        return null;
    }

    /**
     * @param Menu $menu
     * @param string $name
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
     * @param $name
     * @return string
     */
    public function render($name)
    {
        if ($menu = self::get($name)) {
            return $menu->render();
        }

        return '';
    }

    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset) : bool
    {
        return array_key_exists($offset, self::$menus);
    }

    /**
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
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        self::set($value, $offset);
    }

    /**
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        if (array_key_exists($offset, self::$menus)) {
            unset(self::$menus[$offset]);
        }
    }
}