<?php

namespace k1app;

use k1lib\html\foundation\off_canvas as off_canvas;
use k1lib\html\foundation\title_bar as title_bar;
use k1lib\html\foundation\top_bar as top_bar;
use k1lib\html\foundation\menu as menu;

class frontend extends \k1lib\html\DOM {

    static public function start_template() {

        self::html()->body()->init_sections();
    }

}
