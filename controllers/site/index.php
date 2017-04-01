<?php

namespace k1app;

use k1lib\urlrewrite\url as url;
use k1lib\session\session_db as session_db;
use k1lib\html\template as template;

/*
 * APP START
 */
\k1lib\session\session_plain::set_session_name("K1APP-SITE-DEV");
\k1lib\session\session_plain::set_app_user_levels([
    'user',
    'guest'
]);


$app_session = new session_db($db);
$app_session->start_session();
$app_session->load_logged_session_db();


$controller_to_include = url::set_next_url_level(APP_CONTROLLERS_PATH, FALSE, 'controler-name');
$actual_url_value = url::get_url_level_value();

// Template init
template::load_template('scripts/init');
frontend::start_template();
if (!$controller_to_include) {
    require 'home.php';
} else {
    require $controller_to_include;
}
// APP Debug output
template::load_template('verbose-output');
// Template end
template::load_template('scripts/end');

