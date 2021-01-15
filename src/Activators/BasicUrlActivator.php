<?php

namespace Nethead\Menu\Activators;

use Nethead\Menu\Contracts\ActivableItem;
use Nethead\Menu\Contracts\ActivatorInterface;
use Nethead\Menu\Items\Item;

/**
 * BasicUrlActivator can test your items against the current request URL.
 * You can create you own Activators by implementing the Nethead\Menu\Contracts\ActivatorInterface.
 *
 * @see ActivatorInterface
 * @package Nethead\Menu\Activators
 */
class BasicUrlActivator implements ActivatorInterface {
    /**
     * Current URL found in $_SERVER
     *
     * @var mixed
     */
    protected $currentUrl;

    /**
     * Current URL parsed into array.
     *
     * @var array
     */
    protected $parsedCurrentUrl;

    /**
     * Current URL's query string exploded into array.
     *
     * @var array
     */
    protected $explodedQuery = [];

    /**
     * Custom activator or null if it isn't set.
     *
     * @var null|Closure
     * @var null
     */
    protected $customActivator = null;

    /**
     * BasicUrlActivator constructor.
     */
    public function __construct()
    {
        $this->currentUrl = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] ?? '';
        $this->parsedCurrentUrl = parse_url($this->currentUrl);

        if (false === $this->parsedCurrentUrl) {
            throw new \RuntimeException('The current URL is seriously malformed!');
        }

        if (isset($this->parsedCurrentUrl['query'])) {
            $this->explodedQuery = $this->explodeQueryString($this->parsedCurrentUrl['query']);
        }
    }

    /**
     * Test the ActivableItem with the current URL.
     * If the Item's URL is the same as current request URL, the Item will
     * be set as active. Since only Internal items implements the ActivableItem
     * interface, there is no need to compare the domains, and this activator
     * assumes that all Internal items will indeed be internal and the domain
     * will match, so it only checks for the URI and query parameters.
     * URL fragment can't be checked, as it isn't send do the server in HTTP request.
     *
     * @param ActivableItem $item
     *  The item to test against the current URL.
     * @return bool
     *  TRUE if the tested item URL matches the current URL.
     *  FALSE otherwise.
     */
    public function test(ActivableItem $item) : bool
    {
        $url = $item->getUrl();

        if (empty($this->currentUrl)) {
            return false;
        }

        // if the urls are the same, return true right away
        if ($url === $this->currentUrl)
            return true;

        $parsedItemUrl = parse_url($url);

        // if paths are different, no way it's a match
        if ($parsedItemUrl['path'] !== $this->parsedCurrentUrl['path']) {
            return false;
        }

        // if current URL has a query string and item doesn't - no match
        if (isset($this->parsedCurrentUrl['query']) && ! isset($parsedItemUrl['query'])) {
            return false;
        }

        // if item has a query string and current URL doesn't - no match
        if (isset($parsedItemUrl['query']) && ! isset($this->parsedCurrentUrl['query'])) {
            return false;
        }

        // if both item and current URLs have query string, we need to check further
        if (isset($parsedItemUrl['query']) && isset($this->parsedCurrentUrl['query'])) {
            // if the query strings are the same, it's a match
            if ($parsedItemUrl['query'] === $this->parsedCurrentUrl['query']) {
                return true;
            }

            // if not, we have to check if the query params aren't mixed up
            $explodedItemQuery = $this->explodeQueryString($parsedItemUrl['query']);

            foreach($explodedItemQuery as $param => $value) {
                if (! array_key_exists($param, $this->explodedQuery)) {
                    return false;
                }

                if ($this->explodedQuery[$param] != $explodedItemQuery[$param]) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Set the custom activation function to process items as you want.
     *
     * @param \Closure $activator
     *  The function for performing additional actions on the activated item.
     *  Item instance will be injected as a first parameter.
     */
    public function setCustomActivator(\Closure $activator)
    {
        $this->customActivator = $activator;
    }

    /**
     * Activates the item.
     * Also checks for the parent items, and activates those which
     * implements ActivableItem interface up to the root of the
     * items tree. If you've set your custom activator, it will
     * call your function and inject the Item as a first parameter.
     *
     * @param Item $item
     *  Item to activate
     * @return void
     */
    public function activate(Item $item)
    {
        if ($item instanceof ActivableItem) {
            $item->setActive(true);
        }

        if (! is_null($this->customActivator)) {
            call_user_func($this->customActivator, $item);
        }

        if ($item->hasParent()) {
            $this->activate($item->getParent());
        }
    }

    /**
     * Explode the query string into array.
     * Query vars will be converted to array keys.
     *
     * @param string $query
     *  Query string to explode, like someVar=someValue&test=yes
     * @return array
     *  Query array in format:
     *  [someVar => someValue, test => yes]
     */
    public function explodeQueryString(string $query): array
    {
        $parts = explode('&', $query);

        $explodedQuery = [];

        foreach($parts as $part) {
            list($key, $value) = explode('=', $part);
            $explodedQuery[$key] = $value;
        }

        return $explodedQuery;
    }
}