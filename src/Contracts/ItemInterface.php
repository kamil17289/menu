<?php

namespace Nethead\Menu\Contracts;

use Nethead\Menu\Items\Item;

/**
 * Basic interface which defines how the menu Items should work.
 */
interface ItemInterface
{
    /**
     * @param Item $child
     * @return mixed
     */
    public function addChild(Item $child);

    /**
     * @param \Closure $creator
     * @param array $config
     * @return mixed
     */
    public function group(\Closure $creator, array $config = []);

    /**
     * @return bool
     */
    public function hasChildren(): bool;

    /**
     * @return array
     */
    public function getChildren(): array;

    /**
     * @return bool
     */
    public function hasParent(): bool;

    /**
     * @return mixed
     */
    public function getParent();

    /**
     * @param string $template
     * @return mixed
     */
    public function setTemplate(string $template);

    /**
     * @return mixed
     */
    public function getTemplate();

    /**
     * @return string
     */
    public function render(): string;
}