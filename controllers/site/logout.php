<?php

namespace k1app;

// This might be different on your proyect

use k1lib\html\template as template;
use \k1lib\urlrewrite\url as url;
use k1lib\session\session_db as session_db;
use k1lib\notifications\on_frontend as frontend_notifications;

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

$app_session->unset_coockie(APP_BASE_URL);
\k1lib\session\session_plain::end_session();

//d(APP_BASE_URL);

\k1lib\html\html_header_go(APP_URL . 'site/');
