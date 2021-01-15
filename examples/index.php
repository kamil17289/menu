<?php
include '../vendor/autoload.php';
include 'menu.php';

use Nethead\Markup\Foundation\Document;
use Nethead\Menu\Repository;

$document = new Document('en');

$document->title('Easy menu rendition with Nethead\\Menu');

$document->body()->addChildren([
    'menu' => Repository::get('main-menu')
]);

print $document;