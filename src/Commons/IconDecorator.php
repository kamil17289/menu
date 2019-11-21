<?php

namespace Nethead\Menu\Commons;

use Nethead\Markup\Html\Tag;
use RuntimeException;

/**
 * Trait IconDecorator
 * @package Nethead\Menu\Commons
 */
trait IconDecorator {
    /**
     * @var array
     */
    protected $icons = [];

    /**
     * @var string|callable
     */
    public static $iconsProvider = 'fontAwesome';

    /**
     * @param string $iconClass
     * @param string $position
     */
    public function setIcon(string $iconClass, string $position = 'left')
    {
        if (array_key_exists($position, $this->icons)) {
            $this->icons[$position][] = $iconClass;
        }
        else {
            $this->icons[$position] = [$iconClass];
        }
    }

    /**
     * @param string $position
     * @return string
     */
    public function renderIcons(string $position) : string
    {
        if (is_string(self::$iconsProvider)) {
            $provider = self::$iconsProvider . 'IconProvider';

            if (method_exists($this, $provider)) {
                static::$iconsProvider = [$this, $provider];
            }
        }

        if (! is_callable(static::$iconsProvider)) {
            throw new RuntimeException('Icons Provider is not callable!');
        }

        $icons = '';

        if (isset($this->icons[$position])) {
            foreach($this->icons[$position] as $iconClass) {
                $icons .= $this->renderIcon($iconClass);
            }
        }

        return $icons;
    }

    /**
     * @param string $iconClass
     * @return mixed
     */
    protected function renderIcon(string $iconClass)
    {
        return call_user_func(static::$iconsProvider, $iconClass);
    }

    /**
     * @param string $iconClass
     * @return string
     */
    public function fontAwesomeIconProvider(string $iconClass) : string
    {
        return (new Tag('i', [
            'class' => 'fas fa-' . $iconClass
        ]))->__toString();
    }

    /**
     * @param string $iconClass
     * @return string
     */
    public function bootstrapGlyphiconsIconProvider(string $iconClass) : string
    {
        return (new Tag('span', [
            'aria-hidden' => 'true',
            'class' => 'glyphicon glyphicon-' . $iconClass
        ]))->__toString();
    }
}