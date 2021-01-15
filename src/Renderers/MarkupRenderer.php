<?php

namespace Nethead\Menu\Renderers;

use Nethead\Menu\Contracts\RendererInterface;
use Nethead\Menu\Contracts\ActivableItem;
use Nethead\Menu\Items\SimpleItem;
use Nethead\Markup\Foundation\Tag;
use Nethead\Menu\Items\Separator;
use Nethead\Menu\Items\External;
use Nethead\Menu\Items\Internal;
use Nethead\Menu\Items\Anchor;
use Nethead\Menu\Items\Item;
use Nethead\Markup\Tags\A;
use Nethead\Menu\Items\Special;
use Nethead\Menu\Menu;

/**
 * Markup Renderer class.
 * Markup Renderer is will render the Menu Items using the Nethead\Markup package.
 * You can extend it to customize how the different types of Menu Items are rendered.
 *
 * @package Nethead\Menu\Renderers
 */
class MarkupRenderer implements RendererInterface {
    /**
     * List of Items types that are supported by this renderer.
     * If you want to extend the support, you need to extend MarkupRenderer class.
     *
     * @var array
     */
    protected static $renderers = [
        Menu::class         => 'renderMenu',
        Anchor::class       => 'renderLink',
        Special::class      => 'renderLink',
        External::class     => 'renderLink',
        Internal::class     => 'renderLink',
        SimpleItem::class   => 'renderSimple',
        Separator::class    => 'renderSeparator',
    ];

    /**
     * Use this method to render any of the supported item types.
     *
     * @param object $item
     *  Item object that is extending the Nethead\Menu\Items\Item class.
     * @return string
     *  Menu Item rendered to HTML string.
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
     * Add a new className => renderer binding.
     *
     * @param string $className
     *  Fully qualified class name of the Item you want to support.
     * @param callable $renderer
     *  Callable that will be used to render the new Item type.
     */
    public static function addRenderer(string $className, callable $renderer)
    {
        self::$renderers[$className] = $renderer;
    }

    /**
     * Render a menu separator.
     *
     * @param Separator $item
     * @return string
     */
    protected function renderSeparator(Separator $item)
    {
        $separator = new Tag('li');

        $separator->addChildren([
            new Tag('hr')
        ]);

        return $separator->render();
    }

    /**
     * Render a simple Item.
     *
     * @param SimpleItem $item
     * @return string
     */
    protected function renderSimple(SimpleItem $item)
    {
        $wrapper = new Tag('li');

        $contents[] = new Tag('span', [], $item->getInnerHtml());

        if ($item->hasChildren()) {
            $children = array_map([$this, 'render'], $item->getChildren());

            $childrenWrapper = new Tag('ul', [], $children);

            $contents[] = $childrenWrapper;
        }

        $wrapper->setChildren($contents);

        return $wrapper->render();
    }

    /**
     * Renders External, Internal and Anchors items.
     *
     * @param Item $item
     * @return string
     */
    protected function renderLink(Item $item)
    {
        $wrapper = new Tag('li');

        $link = new A($item->getUrl(), $item->getInnerHtml());

        if ($item instanceof ActivableItem && $item->isActive()) {
            $link->classList()->add('-active');

            if ($item->hasChildren()) {
                $link->classList()->add('-expanded');
            }
        }

        $contents = [$link];

        if ($item->hasChildren()) {
            $children = array_map([$this, 'render'], $item->getChildren());

            $childrenWrapper = new Tag('ul', [], $children);

            $contents[] = $childrenWrapper;
        }

        $wrapper->setChildren($contents);

        return $wrapper->render();
    }

    /**
     * Render the whole Menu object.
     *
     * @param Menu $menu
     *  Menu instance that will be rendered to HTML string
     * @return string
     *  The HTML string
     */
    protected function renderMenu(Menu $menu): string
    {
        $nav = new Tag('nav', ['class' => $menu->getName()]);

        $list = new Tag('ul');

        $items = array_map([$this, 'render'], $menu->getItems());

        $list->addChildren($items);

        $nav->addChildren(['list' => $list]);

        return $nav->render();
    }
}