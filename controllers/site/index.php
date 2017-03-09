<?php

namespace k1app;

use k1lib\urlrewrite\url as url;
use k1lib\session\session_db as session_db;
use k1lib\html\template as template;

// Template init
template::load_template('scripts/init');

frontend::start_template();

$controller_to_include = url::set_next_url_level(APP_CONTROLLERS_PATH, FALSE, 'controler-name');

if (!$controller_to_include) {
    require 'home.php';
} else {
    require $controller_to_include;
}

// APP Debug output
template::load_template('verbose-output');
// Template end
template::load_template('scripts/end');
