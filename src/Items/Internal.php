<?php

namespace Nethead\Menu\Items;

use Nethead\Menu\Menu;

class Internal extends External {
    protected $active = false;

    public function activate()
    {
        $this->active = Menu::getActivator()
            ->isActive($this->getHtmlAttribute('href'));
    }
}