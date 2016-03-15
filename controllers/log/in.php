<?php

namespace k1app;

use k1lib\urlrewrite\url as url;

\k1lib\common\check_on_k1lib();


$login_user_input = "login";
$login_password_input = "pass";
$login_remember_me = "remember-me";

$user_data = [];
$login_table = "users";
$login_user_field = "user_login";
$login_password_field = "user_password";
$login_level_field = "user_level";

if (!isset($app_session)) {
    $app_session = new \k1lib\session\session_db($db);
}
$app_session->set_config($login_table, $login_user_field, $login_password_field, $login_level_field);
$app_session->set_inputs($login_user_input, $login_password_input, $login_remember_me);

// chekc the magic value
$post_data = $app_session->catch_post();
if ($post_data) {
    $app_session_check = $app_session->check_login();
    if ($app_session_check) {


        $user_data = array_merge($user_data, $app_session_check);
//        unset($user_data[$login_password_field]);
        // CLEAR ALL
//        $app_session->unset_coockie(APP_BASE_URL);
        $app_session->end_session();
        // BEGIN ALL AGAIN
        $app_session->start_session();
        // SET THE LOGGED SESSION
        $app_session->save_data_to_coockie(APP_BASE_URL);
        if ($app_session->load_data_from_coockie($db)) {
            if (\k1lib\urlrewrite\get_back_url(TRUE)) {
                \k1lib\html\html_header_go(url::do_url(\k1lib\urlrewrite\get_back_url(TRUE)));
            } else {
                \k1lib\html\html_header_go(url::do_url(APP_HOME_URL));
            }
        } else {
            trigger_error("Login with coockie not possible", E_USER_ERROR);
        }
    } elseif ($app_session_check === NULL) {
        \k1lib\html\html_header_go(url::do_url(APP_URL . "log/form?error=no-data"));
    } elseif ($app_session_check === FALSE) {
        \k1lib\html\html_header_go(url::do_url(APP_URL . "log/form?error=bad-login"));
    }
} elseif ($post_data === FALSE) {
    \k1lib\html\html_header_go(url::do_url(APP_URL . "log/form?error=bad-magic"));
} elseif ($post_data === NULL) {
    \k1lib\html\html_header_go(url::do_url(APP_URL . "log/form?error=no-data"));
}