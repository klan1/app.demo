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


$controller_to_include = url::set_next_url_level(APP_CONTROLLERS_PATH, TRUE, 'controler-name');
require $controller_to_include;

$send_id = url::set_url_rewrite_var(url::get_url_level_count(), 'ecard_send_id', TRUE);

$ecard_sends_table = new \k1lib\crudlexs\class_db_table($db, 'ecard_sends');
$ecard_sends_table->set_query_filter(['send_id' => $send_id]);


$ecard_sends_data = $ecard_sends_table->get_data(FALSE);




