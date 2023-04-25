<?php

use Nethead\Menu\Factories\MenusFactory;

$callback = require "make.php";

MenusFactory::make('main-menu', $callback);

