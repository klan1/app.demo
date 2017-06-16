<?php

namespace k1app;

use \k1lib\urlrewrite\url as url;
use k1lib\html\template as template;
use k1lib\session\session_db as session_db;

/*
 * APP START
 */
$app_session = new session_db($db);
$app_session->start_session();
$app_session->load_logged_session_db();

// Template init
template::load_template('scripts/init');

$controller_to_load = url::set_next_url_level(APP_CONTROLLERS_PATH, FALSE);

if (!$controller_to_load) {
    \k1lib\html\html_header_go(url::do_url("./form"));
} else {
    require $controller_to_load;
}

// APP Debug output
template::load_template('verbose-output');
// Template end
template::load_template('scripts/end');
