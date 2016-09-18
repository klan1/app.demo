<?php

/**
 * CONTROLLER WITH DETAIL LIST
 * Ver: 1.0
 * Autor: J0hnD03
 * Date: 2016-02-03
 * 
 */

namespace k1app;

use k1lib\templates\temply as temply;
use k1lib\urlrewrite\url as url;
use k1lib\session\session_plain as session_plain;

if (!\k1lib\session\session_plain::is_logged()) {
    trigger_error("You are not loged yet.", E_USER_ERROR);
}

include temply::load_template("header", APP_TEMPLATE_PATH);


$static_vars_from_get = \k1lib\forms\check_all_incomming_vars($_GET);
unset($static_vars_from_get[\k1lib\URL_REWRITE_VAR_NAME]);

/**
 * URL
 */
////TABLE TO USE
//$table_to_use = url::set_url_rewrite_var(url::get_url_level_count(), "table_to_use", TRUE);
//$table_to_use_real = \k1lib\db\security\db_table_aliases::decode($table_to_use);
//UTILITY TO RUN
$password_utility = url::set_url_rewrite_var(url::get_url_level_count(), "password_utility", TRUE);
//ACTION TO PREFORM
$password_action = url::set_url_rewrite_var(url::get_url_level_count(), "password_action", FALSE);

switch ($password_utility) {
    case 'change':

        if ($password_action == "do") {
            // GET LOGIN CONFIG
            $login = new \k1lib\session\session_db($db);
            $login_config = $login->load_data_from_coockie(TRUE);

            $login_data = [
                $login_config['user_login_field'] => session_plain::get_user_login(),
                $login_config['user_password_field'] => md5($_POST['current-password']),
            ];
            $db_table = new \k1lib\crudlexs\class_db_table($db, $login_config['db_table_name']);
            $db_table->set_query_filter($login_data, TRUE);
            if ($db_table->get_data(FALSE)) {
                if ($_POST['new-password'] == $_POST['verify-password']) {
                    $update_data = [
                        $login_config['user_password_field'] => md5($_POST['new-password']),
                    ];
                    $update_key = [
                        $login_config['user_login_field'] => session_plain::get_user_login(),
                    ];
                    if ($db_table->update_data($update_data, $update_key)) {
                        \k1lib\common\show_message("New password changed successfully.", "", "success");
                        session_plain::end_session();
                    } else {
                        \k1lib\common\show_message("Password did not set, please try again", "", "warning");
                    }
                } else {
                    \k1lib\common\show_message("New password and veriify password do not match, please try again", "", "warning");
                }
            } else {
                \k1lib\common\show_message("Current password is invalid, please try again", "Nothing done", "alert");
            }
        }

        include temply::load_template("password-change", APP_TEMPLATE_PATH);
        break;
    case 'forgot':




        include temply::load_template("password-forgot", APP_TEMPLATE_PATH);
        break;

    default:
        break;
}


include temply::load_template("footer", APP_TEMPLATE_PATH);
