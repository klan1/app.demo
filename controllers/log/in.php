<?php

namespace k1app;

use k1lib\session\session_plain as k1lib_session;
use \k1lib\urlrewrite\url as url;

\k1lib\common\check_on_k1lib();

$login_user_input = "login";
$login_password_input = "pass";

$user_data = [];

$login_table = "users";
$login_user_field = "user_login";
$login_password_field = "user_password";
$login_level_field = "user_level";


// chekc the magic value
if (isset($_POST['magic_value'])) {
    $magic_test = \k1lib\common\check_magic_value("login_form", $_POST['magic_value']);
    if ($magic_test == TRUE) {
        // the form was correct, so lets try to login

        /**
         * Check the _GET incomming vars
         */
        $form_values = \k1lib\forms\check_all_incomming_vars($_POST, "k1lib_login");

        /**
         * Login fields
         */
        $user_login = $form_values[$login_user_input];
        $user_password = md5($form_values[$login_password_input]);

        if (empty($user_login) || empty($user_password)) {
            \k1lib\html\html_header_go(APP_URL . "log/form?error=no-data");
        }

        /**
         * SQL check
         */
        $sql_user_login = "SELECT * FROM " . $login_table
                . " WHERE "
                . $login_user_field . "= '{$user_login}' "
                . " AND " . $login_password_field . "= '{$user_password}'";
        $sql_result = \k1lib\sql\sql_query($db, $sql_user_login, FALSE);
        if (!empty($sql_result)) {
            $user_data = array_merge($user_data, $sql_result);
            unset($user_data[$login_password_field]);
            // CLEAR ALL
            k1lib_session::end_session();
            // BEGIN ALL AGAIN
            k1lib_session::start_session();
            // SET THE LOGGED SESSION
            k1lib_session::start_logged_session($user_data[$login_user_field], $user_data, $user_data[$login_level_field]);
            if (\k1lib\urlrewrite\get_back_url(TRUE)) {
                \k1lib\html\html_header_go(url::do_url(\k1lib\urlrewrite\get_back_url(TRUE)));
            } else {
                \k1lib\html\html_header_go(url::do_url(APP_HOME_URL));
            }
        } else {
            \k1lib\html\html_header_gourl::do_url((APP_URL . "log/form?error=bad-login"));
        }
    } else {
        \k1lib\html\html_header_go(url::do_url(APP_URL . "log/form?error=bad-magic"));
    }
} else {
    \k1lib\html\html_header_go(url::do_url(APP_URL . "log/form?error=no-data"));
}