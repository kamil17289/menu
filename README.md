# Menu Builder
v2.0

This package is able to build HTML menus using the object-oriented PHP API.
Package is distributed with the Packagist and Composer.

## Installation
First, install the package with Composer.

```shell
composer require nethead/menu
```

If you are already working in a project with dependencies managed by Composer 
there is nothing else to do. If not, include the Composer's autoload file:

`include 'vendor/autoload.php';`

## Using the package

### Creating a Menu with MenuFactory

The quickest way to create a new Menu object is to use the MenuFactory class.

```php
use Nethead\Menu\Factories\MenusFactory;

MenusFactory::make('main-menu');
```

That's right - you don't need to assign it to any variable, the MenuFactory 
will automatically store the created menu object in a Repository. Check out 
the Repository chapter below to learn more.

### Creating a Menu manually

If for some reason, you are using the Menu class constructor, you can add the
newly created menus to the repository later, so they will be available in other
scopes. When creating a menu manually, you have to additionally provide the
Activator and the Renderer for this menu.

**Activator** is an object that is able to test the Menu Items and check if the
current request URL is matching the Item's URL. If yes, the Activator will mark
the item as active item, so you can style it accordingly in CSS. There is one
Activator available for you out of the box, you can find it in
`Nethead\Menu\Activators\BasicUrlActivator`.

**Renderer** is an object which is able to render HTML based on the information
provided by the Menu item object. There is one Renderer available for you out of
the box, in `Nethead\Menu\Renderers\MarkupRenderer`. This renderer is using the
Nethead\Markup package which is an easy way to render and process HTML (also OOP).

Creating Menu manually requires you to provide those two dependencies for the Menu
object. Feel free to write your own renderers and activators if you need other way
for rendering or activating items. Just make sure your classes implements
`Nethead\Menu\Contracts\ActivatorInterface` and
`Nethead\Menu\Contracts\RendererInterface` as those are the ones expected by the
Menu.

Now, let's create a menu manually. MenuFactory is taking care of registering our
menu in the Repository, so if we want this one to available in other scopes,
we need to register it with `set` method.

```php
use Nethead\Menu\Activators\BasicUrlActivator;
use Nethead\Menu\Renderers\MarkupRenderer;
use Nethead\Menu\Repository;
use Nethead\Menu\Menu;

$menu = new Menu('sidebar', new BasicUrlActivator(), new MarkupRenderer());

Repository::set($menu);
```

### Retrieving Menus from the Repository
To retrieve the created menu for further operations, use the Repository.

```php
use Nethead\Menu\Repository;

// one created with MenusFactory
$mainMenu    = Repository::get('main-menu');
// one created manually and registered
$sidebarMenu = Repository::get('sidebar');   
```

If you create a Repository class instance, it also supports array access syntax.
Or if you prefer, there is a render() method for quick outputting the menu
(it will output empty string if the menu doesn't exist in the Repository).

```injectablephp
$repository = new Nethead\Menu\Repository();

print $repository['main-menu'];

print $repository->render('sidebar'); // less error prone
```

### Managing Items
Ok, for now the menu doesn't have any items assigned, so it's pretty useless.
Let's add a link to a homepage to this menu. There are 6 types of Items you
can add to your Menu, and you are free to create your own implementations as
long as they extend the `Nethead\Menu\Items\Item`. If you want them to be
set as active items on some URLs, you should also implement the
`Nethead\Menu\Contracts\ActivableItem` interface.

Let's go through all available Item types:
1. Separator - this type is not interactive at all, it only serves as a 
decoration.
2. SimpleItem - it is able to display text, images, anything you want, but is
not interactive. It can serve as a parent item for multilevel menus or act
like a subtitle. It's up to you how you will use it.
3. Anchor - this is a special item type to indicate that the link is internal,
(doesn't go outside your website), but it is an anchor to a place inside 
the currently viewed page. It uses URL fragment (text followed by the # char).
4. External - Item type that lets you direct user to other location outside 
your website.
5. Internal - Item type that lets you take the user to the other location 
within your website.
6. Special - Special item that represents links with schemes other than http://

To add an item to a menu, create an instance of it and assign it.

```php
use Nethead\Menu\Activators\BasicUrlActivator;
use Nethead\Menu\Renderers\MarkupRenderer;
use Nethead\Menu\Items\Internal;
use Nethead\Menu\Menu;

$menu = new Menu('sidebar', new BasicUrlActivator(), new MarkupRenderer());
$item = new Internal(['The text for the link'], '/one/of/your/pages', $menu);
$menu->setItem($item);
```

With this code we create a Menu with standard Activator and Renderer. Next, we
will create the item with a desired href attribute. Next, we assign it to the
sidebar menu. This will render the item inside the menu, when the menu is
outputted in your template. However, this method is not very efficient if you
want to build your menu quickly. It is more useful when you pass the menu object
through some processing functions to let modules or plugins add their own links.
So now, lets take a look at the ItemsFactory class, and it's features.

ItemsFactory instance is a first parameter passed to Menu::createItems and a
second passed to MenusFactory::make. Let's create a menu with a few simple links
using the MenusFactory.

```php
use Nethead\Menu\Factories\MenusFactory;
use Nethead\Menu\Factories\ItemsFactory;

MenusFactory::make('main-menu', function(ItemsFactory $factory) {
    $factory->internal(['Homepage'], '/');
    $factory->external(['Example domain'], 'http://example.com');
    
    $factory->simple(['Table of contents'])->group(function(ItemsFactory $factory) {
        $factory->anchor(['Chapter 1'], 'chapter-1');
        $factory->anchor(['Chapter 2'], 'chapter-2');
        $factory->anchor(['Chapter 3'], 'chapter-3');
    });
    
    $factory->simple(['Contact'])->group(function(ItemsFactory $factory) {
        $factory->special(['Mail me'], 'mailto', 'example@example.com');
        $factory->separator();
        $factory->special(['Call me'], 'tel', '555555555');
    });
});
```

That's it! No worries of calling setItem on any menu. ItemsFactory will take
care of registering all created items and their structure inside the main-menu.

### Reference

For more information on the API look inside the docs directory.

### Credits
This package is developed by Kamil Gałkiewicz and distributed for you on MIT
licence. Feel free to use it if it's suitable for you.