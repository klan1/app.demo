<?php

namespace k1app;

use k1lib\urlrewrite\url as url;
use k1lib\session\session_db as session_db;
use k1lib\html\template as template;


// Template init
template::load_template('scripts/init');

k1app_template::start_template();

if (session_db::is_logged()) {
    $controller_to_include = url::set_next_url_level(APP_CONTROLLERS_PATH, FALSE, 'controler-name');

    if (!$controller_to_include) {
        if (session_db::check_user_level(['god', 'admin'])) {
            $go_url = url::do_url("dashboard-admin/");
        } elseif (session_db::check_user_level(['user'])) {
            $go_url = url::do_url("dashboard-user/");
        } else {
            trigger_error("No idea how you do it!", E_USER_ERROR);
        }
        \k1lib\html\html_header_go($go_url);
    } else {
        require $controller_to_include;
    }
} else {
    $get_params = ["back-url" => $_SERVER['REQUEST_URI']];
    \k1lib\html\html_header_go(url::do_url(APP_URL . "log/form/", $get_params));
}

// APP Debug output
template::load_template('verbose-output');
// Template end
template::load_template('scripts/end');
