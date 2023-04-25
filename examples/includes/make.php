<?php

use Nethead\Menu\Factories\ItemsFactory;
use Nethead\Markup\Helpers\IconsFactory;

return function (ItemsFactory $factory) {
    $factory->anchor(['This is an #anchor'], 'test-id');

    $factory->internal(['This is a group of links'], 'http://localhost:8002/group-links.php')->group(function (ItemsFactory $factory) {
        $factory->external(['Go to Google'], 'https://google.com');
        $factory->internal(['Go to Homepage'], 'http://localhost:8002/index.php');
        $factory->internal(['Go to Homepage 2'], 'http://localhost:8002/');
        $factory->internal(['Go to Homepage 3'], 'http://localhost:8002/index.php?view=article&id=156');
    });

    $factory->separator();

    $factory->simple(['This is a text item'])->group(function(ItemsFactory $factory) {
        $factory->internal([IconsFactory::icon('calendar'), 'some calendar'], '/index.php?view=calendar&type=week');
        $factory->external(['Mail me!'], 'mailto:example@example.com');
        $factory->special(['Give me a call'], 'tel', '555555555');
    });
};
