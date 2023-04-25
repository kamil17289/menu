<?php
include "../vendor/autoload.php";

use Nethead\Menu\Renderers\ArrayRenderer;
use Nethead\Menu\Factories\MenusFactory;
use Nethead\Menu\Repository;

$callback = require "includes/make.php";

MenusFactory::make('main-menu', $callback, null, new ArrayRenderer());

header('Content-Type: application/json');

echo json_encode(Repository::get('main-menu')->render());
