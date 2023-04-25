<?php

namespace Nethead\Menu\Renderers;

use Nethead\Menu\Contracts\ActivableItem;
use Nethead\Menu\Contracts\RendererInterface;
use Nethead\Menu\Items\SimpleItem;
use Nethead\Menu\Items\Separator;
use Nethead\Menu\Items\External;
use Nethead\Menu\Items\Internal;
use Nethead\Menu\Items\Special;
use Nethead\Menu\Items\Anchor;
use Nethead\Menu\Items\Item;
use Nethead\Menu\Menu;

/**
 * Array Renderer class.
 * Array Renderer will convert the Menu into a "jsonable" array.
 * You can extend it to customize how the different types of Menu Items are rendered.
 */
class ArrayRenderer implements RendererInterface {
    /**
     * List of Items supported by this renderer.
     *
     * @var string[]
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
     * @param object $item
     * @return array
     */
    public function render(object $item): array
    {
        $itemType = get_class($item);

        if (! array_key_exists($itemType, static::$renderers)) {
            throw new \RuntimeException('This renderer doesn\'t support provided item type!');
        }

        $renderer = static::$renderers[$itemType];

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
        static::$renderers[$className] = $renderer;
    }

    /**
     * @param Separator $item
     * @return array
     */
    protected function renderSeparator(Separator $item): array
    {
        return [
            'type' => 'separator',
        ];
    }

    /**
     * @param SimpleItem $item
     * @return array
     */
    protected function renderSimple(SimpleItem $item): array
    {
        return [
            'type' => 'simple',
            'contents' => $item->getInnerHtml(),
            'children' => $item->hasChildren() ? array_map([$this, 'render'], $item->getChildren()) : []
        ];
    }

    protected function renderLink(Item $item): array
    {
        $active = $item instanceof ActivableItem && $item->isActive();

        return [
            'type' => 'link',
            'url' => $item->getUrl(),
            'active' => $active,
            'expanded' => $active && $item->hasChildren(),
            'contents' => $item->getInnerHtml(),
            'children' => $item->hasChildren() ? array_map([$this, 'render'], $item->getChildren()) : []
        ];
    }

    /**
     * @param Menu $menu
     * @return array
     */
    protected function renderMenu(Menu $menu): array
    {
        return [
            'menu' => $menu->getName(),
            'items' => array_map([$this, 'render'], $menu->getItems())
        ];
    }
}
