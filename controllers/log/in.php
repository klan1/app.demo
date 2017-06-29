<?php

namespace k1app;

use k1lib\notifications\on_DOM as DOM_notifications;
use k1lib\urlrewrite\url as url;

$login_user_input = "login";
$login_password_input = "pass";
$login_remember_me = "remember-me";

$user_data = [];
if (isset($_POST['login-type'])) {
    if ($_POST['login-type'] == 'agency') {

        $login_table = "view_users_complete_data";
        $login_user_field = "user_login";
        $login_password_field = "user_password";
        $login_level_field = "user_level";
    } elseif ($_POST['login-type'] == 'client') {

        $login_table = "contacts";
        $login_user_field = "contact_login";
        $login_password_field = "contact_password";
        $login_level_field = "user_level";
    } else {

        trigger_error("Bad hacker, bad hacker... don't do that !!!", E_USER_ERROR);
    }
    if (!isset($app_session)) {
        $app_session = new \k1lib\session\session_db($db);
    }
    $app_session->set_config($login_table, $login_user_field, $login_password_field, $login_level_field);
    $app_session->set_inputs($login_user_input, $login_password_input, $login_remember_me);
} else {
    DOM_notifications::queue_mesasage("Incorrect login form", "alert");
}

// chekc the magic value
$post_data = $app_session->catch_post();
if ($post_data) {
    $app_session_check = $app_session->check_login();
    if ($app_session_check) {
        $user_data = array_merge($user_data, $app_session_check);
        // unset($user_data[$login_password_field]);
        // CLEAR ALL
        // $app_session->unset_coockie(APP_BASE_URL);
        $app_session->end_session();
        // BEGIN ALL AGAIN
        $app_session->start_session();
        // SET THE LOGGED SESSION
        $app_session->save_data_to_coockie(APP_BASE_URL);
        if ($app_session->load_data_from_coockie($db)) {
            DOM_notifications::queue_mesasage("welcome!", "success");
            if (\k1lib\urlrewrite\get_back_url(TRUE)) {
                \k1lib\html\html_header_go(url::do_url(\k1lib\urlrewrite\get_back_url(TRUE)));
            } else {
                \k1lib\html\html_header_go(url::do_url(APP_HOME_URL));
            }
        } else {
            trigger_error("Login with coockie not possible", E_USER_ERROR);
        }
    } elseif ($app_session_check === NULL) {
        DOM_notifications::queue_mesasage("No se han recibido datos", "warning");
    } elseif ($app_session_check === array()) {
        DOM_notifications::queue_mesasage("Bad password or login", "alert");
    }
} elseif ($post_data === FALSE) {
    DOM_notifications::queue_mesasage("BAD, BAD Magic!!", "warning");
} elseif ($post_data === NULL) {
    DOM_notifications::queue_mesasage("No se han recibido datos", "warning");
}
\k1lib\html\html_header_go(url::do_url(APP_URL . "log/form/"));
