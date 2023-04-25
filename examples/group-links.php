<?php
include '../vendor/autoload.php';
include 'includes/menu.php';

use Nethead\Menu\Repository;

$document = new \Nethead\Markup\Foundation\Document('en');

$document->title('Easy menu rendition with Nethead\\Menu');

$document->body()->addChildren([
    'menu' => Repository::get('main-menu')->render()
]);

print $document;
