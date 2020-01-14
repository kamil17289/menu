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
 * Class MarkupRenderer
 * @package Nethead\Menu\Renderers
 */
class MarkupRenderer implements RendererInterface {
    /**
     * List of classes along with their render methods
     * @var array
     */
    protected static $renderers = [
        Menu::class         => 'renderMenu',
        Anchor::class       => 'renderLink',
        External::class     => 'renderLink',
        Internal::class     => 'renderLink',
        TextItem::class     => 'renderText',
        Separator::class    => 'renderSeparator',
    ];

    /**
     * Use this method to render any of the supported classes
     * @param object $item
     * @return string
     */
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

    /**
     * Add a new className => renderer binding
     * @param string $className
     * @param callable $renderer
     */
    public static function addRenderer(string $className, callable $renderer)
    {
        self::$renderers[$className] = $renderer;
    }

    /**
     * @param Item $item
     * @return string
     */
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

    /**
     * @param TextItem $item
     * @return string
     */
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

    /**
     * @param Separator $item
     * @return string
     */
    protected function renderSeparator(Separator $item)
    {
        $separator = new Tag('li', [], new Tag('hr'));

        return $separator->__toString();
    }

    /**
     * @param Menu $menu
     * @return Tag
     */
    protected function renderMenu(Menu $menu)
    {
        $nav = new Tag('nav', ['class' => 'menu ' . $menu->getName()]);

        $list = new Tag('ul');

        $items = array_map([$this, 'render'], $menu->getItems());

        $list->setContents($items);

        $nav->setContents($list);

        return $nav;
    }
}