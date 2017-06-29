<?php

namespace k1app;

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

k1app_template::start_template_plain();

require \k1lib\urlrewrite\url::set_next_url_level(APP_CONTROLLERS_PATH, TRUE);

// APP Debug output
template::load_template('verbose-output');
// Template end
template::load_template('scripts/end');
