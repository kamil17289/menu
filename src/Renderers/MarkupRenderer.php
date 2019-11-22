<?php

namespace Nethead\Menu\Renderers;

use Nethead\Menu\Contracts\RendererInterface;
use Nethead\Menu\Items\Separator;
use Nethead\Menu\Items\External;
use Nethead\Menu\Items\Internal;
use Nethead\Menu\Items\TextItem;
use Nethead\Menu\Items\Anchor;
use Nethead\Menu\Items\Item;
use Nethead\Markup\Html\Tag;
use Nethead\Markup\Html\A;
use Nethead\Menu\Menu;

/**
 * Class ObRenderer
 * @package Nethead\Menu\Renderers
 */
class MarkupRenderer implements RendererInterface {
    protected static $renderers = [
        Menu::class         => 'renderMenu',
        Anchor::class       => 'renderLink',
        External::class     => 'renderLink',
        Internal::class     => 'renderLink',
        TextItem::class     => 'renderText',
        Separator::class    => 'renderSeparator',
    ];

    public function render(object $item) : string
    {
        $itemType = get_class($item);

        if (! array_key_exists($itemType, self::$renderers)) {
            throw new \RuntimeException('This renderer doesn\'t support provided item type!');
        }

        $renderer = self::$renderers[$itemType];

        if (is_string($renderer) && method_exists($this, $renderer)) {
            return call_user_func([$this, $renderer], $item);
        }

        return call_user_func($renderer, $item);
    }

    public static function addRenderer(string $className, callable $renderer)
    {
        self::$renderers[$className] = $renderer;
    }

    protected function renderLink(Item $item)
    {
        $wrapper = new Tag('li');

        $contents = [new A($item->getHtmlAttribute('href'), $item->getDisplayText(), $item->getHtmlAttributes())];

        if ($item->hasChildren()) {
            $children = array_map([$this, 'render'], $item->getChildren());

            $childrenWrapper = new Tag('ul', [], $children);

            $contents[] = $childrenWrapper;
        }

        $wrapper->setContents($contents);

        return $wrapper->__toString();
    }

    protected function renderText(TextItem $item)
    {
        $wrapper = new Tag('li');

        $contents = [new Tag('span', [], $item->getDisplayText())];

        if ($item->hasChildren()) {
            $children = array_map([$this, 'render'], $item->getChildren());

            $childrenWrapper = new Tag('ul', [], $children);

            $contents[] = $childrenWrapper;
        }

        $wrapper->setContents($contents);

        return $wrapper->__toString();
    }

    protected function renderSeparator(Separator $item)
    {
        $separator = new Tag('li', [], new Tag('hr'));

        return $separator->__toString();
    }

    protected function renderMenu(Menu $menu)
    {

    }
}