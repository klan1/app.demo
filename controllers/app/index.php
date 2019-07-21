<?php

namespace k1app;

use k1lib\urlrewrite\url as url;
use k1lib\session\session_db as session_db;
use k1lib\html\template as template;

include 'db.php';
include 'db-tables-aliases.php';
include 'controllers-config.php';

/*
 * APP START
 */
session_db::set_session_name(SESSION_APP_NAME);
$app_session = new session_db($db);
$app_session->start_session();
$app_session->load_logged_session_db();

//\k1lib\session\session_db::is_logged(TRUE, APP_URL . 'roy-2019/log/form/');

// Template init
template::load_template('scripts/init');

//k1app_template::start_template();

$controller_to_include = url::set_next_url_level(APP_CONTROLLERS_PATH, FALSE, 'controller_to_include');
if ($controller_to_include) {
    require $controller_to_include;
} else {
    if (session_db::is_logged()) {
        if (!$controller_to_include) {
            if (session_db::check_user_level(['god', 'admin'])) {
                $go_url = url::do_url("dashboard/");
            } elseif (session_db::check_user_level(['user'])) {
                $go_url = url::do_url("dashboard/");
            } else {
                trigger_error("No idea how you do it!", E_USER_ERROR);
            }
            \k1lib\html\html_header_go($go_url);
        } else {
            require $controller_to_include;
        }
    } else {
        /**
         * REMOVE THIS !! when the login system is setup
         */
        $go_url = url::do_url("log/form/");
        \k1lib\html\html_header_go($go_url);
        /**
         * UNCOMMENT THIS !! when the login system is setup
         */
//    $get_params = ["back-url" => $_SERVER['REQUEST_URI']];
//    \k1lib\html\html_header_go(url::do_url(APP_URL . "log/form/", $get_params));
    }
}
// APP Debug output
template::load_template('verbose-output');
// Template end
template::load_template('scripts/end');
