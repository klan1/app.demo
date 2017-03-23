<?php

namespace k1app;

use k1lib\urlrewrite\url as url;
use k1lib\html\template as template;

//frontend::html()->decatalog();
$body = frontend::html()->body();
$body->content()->set_class('page');

template::load_template('html-head');
template::load_template('app-header');

$controller_to_load = url::set_next_url_level(APP_CONTROLLERS_PATH, FALSE);

if (!$controller_to_load) {
    \k1lib\html\html_header_go(url::do_url("./test"));
} else {
    require $controller_to_load;
}


template::load_template('app-footer');

