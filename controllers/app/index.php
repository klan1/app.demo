<?php

namespace k1app;

use k1lib\urlrewrite\url as url;
use k1lib\session\session_db as session_db;


k1app_template::start_template();

if (session_db::is_logged()) {
    $controller_to_include = url::set_next_url_level(APP_CONTROLLERS_PATH, FALSE);

    if (!$controller_to_include) {
        \k1lib\html\html_header_go(url::do_url(APP_URL . 'app/clientes/'));
    } else {
        require $controller_to_include;
    }
} else {
    $get_params = ["back-url" => $_SERVER['REQUEST_URI']];
    \k1lib\html\html_header_go(url::do_url(APP_URL . "log/form/", $get_params));
}

