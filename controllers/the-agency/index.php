<?php

namespace k1app;

use k1lib\urlrewrite\url as url;
use k1lib\session\session_db as session_db;

if (session_db::is_logged()) {
    $controller_to_include = url::set_next_url_level(APP_CONTROLLERS_PATH, FALSE);

    if (!$controller_to_include) {
        if (session_db::check_user_level(['god', 'admin'])) {
            $go_url = url::do_url("my-agency/");
        } elseif (session_db::check_user_level(['user'])) {
            $get_params = ["auth-code" => md5(\k1lib\K1MAGIC::get_value() . session_db::get_user_login())];
            $go_url = url::do_url(APP_URL . agency_users_config::ROOT_URL . '/' . agency_users_config::BOARD_READ_URL . '/' . session_db::get_user_login() . "/", $get_params);
        } elseif (session_db::check_user_level(['client'])) {
            $get_params = ["auth-code" => md5(\k1lib\K1MAGIC::get_value() . session_db::get_user_data()['client_id'])];
            $go_url = url::do_url(APP_URL . agency_users_config::ROOT_URL . '/' . agency_users_config::BOARD_READ_URL . '/' . session_db::get_user_data()['client_id'] . "/", $get_params);
        } else {
            trigger_error("No idea how you do it!", E_USER_ERROR);
        }
        \k1lib\html\html_header_go($go_url);
    } else {
        require $controller_to_include;
    }
} else {
    $get_params = ["back-url" => $_SERVER['REQUEST_URI']];

    \k1lib\html\html_header_go(url::do_url(APP_URL . "log/form/", $get_params));
}

