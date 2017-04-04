<?php

namespace k1app;

use k1lib\urlrewrite\url as url;
use k1lib\session\session_db as session_db;
use k1lib\html\template as template;

include 'ecard-generation.php';
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
/**
 * READ VALUES AND MAKE CHECKS
 */
// AUTH CODE
$send_id = url::set_url_rewrite_var(url::get_url_level_count(), 'ecard_send_id', TRUE);
$auth_code = url::set_url_rewrite_var(url::get_url_level_count(), 'auth_code', TRUE);

if (!empty($auth_code) && (check_ecard_id_auth_code($send_id, $auth_code))) {

    $ecard_sends_table = new \k1lib\crudlexs\class_db_table($db, 'ecard_sends');
    $ecard_sends_table->set_query_filter(['send_id' => $send_id]);

    $ecard_sends_data = $ecard_sends_table->get_data(FALSE);
    $ecard = new ecard_generator($ecard_sends_data['ecard_id'], $ecard_sends_data['ecard_mode'], $ecard_sends_data['send_id']);

    $ecard_thumb = APP_URL . url::get_this_url(-1) . 'email-thumb/';
    $ecard_download = APP_URL . url::get_this_url(-1) . 'download/';

    $get_action = url::set_url_rewrite_var(url::get_url_level_count(), 'get_action', TRUE);

    /**
     * LOAD CONTROLER TO GET HTML
     */
    $controller_to_include = \k1lib\controllers\load_controller($get_action, APP_CONTROLLERS_PATH . 'get-ecard');
    require $controller_to_include;
} else {

    echo "Excus Me, want do you want here ? (\_/)";
}
