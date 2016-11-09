<?php

namespace k1app;

use \k1lib\session\session_db as k1lib_session;
use \k1lib\urlrewrite\url as url;
use k1app\k1app_template as DOM;

$body = DOM::html()->body();

if (DOM::off_canvas()) {
    DOM::off_canvas()->left()->set_class('reveal-for-large', TRUE);
}

if (!isset($_GET['just-controller'])) {

    DOM::set_title(1, APP_TITLE);
    DOM::set_title(2, ' :: ');
    DOM::set_title(3, '');

    $menu_left = DOM::menu_left();
    $menu_left_tail = DOM::menu_left_tail();

    /**
     * AUTO APP
     */
    if (k1lib_session::is_logged()) {

        /**
         * CLIENT USER
         */
        if (\k1lib\session\session_db::check_user_level(['client'])) {
            $auth_code = "?auth-code=" . md5(\k1lib\K1MAGIC::get_value() . session_db::get_user_data()['client_id'] . '--' . session_db::get_user_data()['agency_id']);
            $client_url = APP_URL . client_clients_config::ROOT_URL . '/' . client_clients_config::BOARD_READ_URL . '/' . session_db::get_user_data()['client_id'] . '--' . session_db::get_user_data()['agency_id'] . "/" . $auth_code;

            $li = $top_bar->add_menu_item("#", session_db::get_user_login());
            $sub_menu = $top_bar->add_sub_menu($li);
            $top_bar->add_menu_item($client_url, "My business", $sub_menu);

            $admin_menu = $menu_left_tail->add_sub_menu('#', 'App preferences', 'nav-app-preferences');

            $admin_menu->add_menu_item(APP_URL . 'table-explorer/show-tables/', 'Table Explorer', 'nav-table-explorer');
            $admin_menu->add_menu_item(APP_URL . 'table-metadata/show-tables/', 'Manage tables', 'nav-manage-tables');
            $admin_menu->add_menu_item(APP_URL . 'table-metadata/load-field-comments/', 'Load fields metadata', 'nav-fields-metadata');
            $admin_menu->add_menu_item(APP_URL . 'table-metadata/export-field-comments/', 'Export field metadata', 'nav-export-fields-meta')->set_attrib('target', '_blank');
        }

        /**
         * THE AGENCY
         */
        if (\k1lib\session\session_db::check_user_level(['god', 'admin', 'user'])) {

            $li = $top_bar->add_menu_item("#", "Agency");
            $sub_menu = $top_bar->add_sub_menu($li);
            if (\k1lib\session\session_db::check_user_level(['god', 'admin'])) {
                $top_bar->add_menu_item(APP_URL . "the-agency/my-agency/", "My Agency", $sub_menu);
                $top_bar->add_menu_item(APP_URL . "the-agency/locations/", "Locations", $sub_menu);
                $top_bar->add_menu_item(APP_URL . "the-agency/departments/", "Departments", $sub_menu);
                $top_bar->add_menu_item(APP_URL . "the-agency/job-titles/", "Jobs Titles", $sub_menu);
            }
            $top_bar->add_menu_item(APP_URL . "the-agency/users/", "Users", $sub_menu);
        }
        /**
         * THE CLIENTS
         */
        if (\k1lib\session\session_db::check_user_level(['god', 'admin', 'user'])) {

            $li = $top_bar->add_menu_item("#", "Clients");
            $sub_menu = $top_bar->add_sub_menu($li);
            $top_bar->add_menu_item(APP_URL . "the-clients/clients/", "View clients", $sub_menu);
            if (\k1lib\session\session_db::check_user_level(['god', 'admin'])) {
                $top_bar->add_menu_item(APP_URL . "the-clients/contacts/", "Clients contacts", $sub_menu);
                $top_bar->add_menu_item(APP_URL . "the-clients/contracts/", "Contracts", $sub_menu);
                $top_bar->add_menu_item(APP_URL . "the-clients/projects/", "Projects", $sub_menu);
            }
        }
        /**
         * TASK ORDERS
         */
        if (\k1lib\session\session_db::check_user_level(['god', 'admin'])) {

            $top_bar->add_menu_item(APP_URL . "the-clients/task-orders/", "Task orders");
        }
        /**
         * USER PROFILE
         */
        if (\k1lib\session\session_db::check_user_level(['god', 'admin', 'user'])) {
            $auth_code = "?auth-code=" . md5(\k1lib\K1MAGIC::get_value() . session_db::get_user_login());
            $user_url = APP_URL . agency_users_config::ROOT_URL . '/' . agency_users_config::BOARD_READ_URL . '/' . session_db::get_user_login() . "/" . $auth_code;

            $li = $menu_right->append_li();
            $li->append_a("#", session_db::get_user_login());

            $sub_menu = $top_bar->add_sub_menu($li);
            $top_bar->add_menu_item($user_url, "My profile", $sub_menu);
//        $top_bar->add_menu_item(APP_URL . "general-utils/user-password/change/", "Change password", $sub_menu);
        }
        /**
         * AUTO APP
         */

        if (k1lib_session::is_logged()) {
            /**
             * APP Preferences
             */
            if (\k1lib\session\session_plain::check_user_level(['god'])) {

                $sub_menu = $top_bar->add_sub_menu($li);
                $top_bar->add_menu_item(APP_URL . "table-explorer/show-tables/", "DB tables", $sub_menu);
                $top_bar->add_menu_item(APP_URL . "table-metadata/show-tables/", "Manage tables", $sub_menu);
                $li = $top_bar->add_menu_item(APP_URL . "table-metadata/export-field-comments/", "Export field comments", $sub_menu);
                $li->get_child(0)->set_attrib("target", "_blank");
                $top_bar->add_menu_item(APP_URL . "table-metadata/load-field-comments/", "Load field comments", $sub_menu);
            }

            $top_bar->add_button(APP_URL . "log/out/", "Salir", "alert");
        } else {
            $top_bar->add_button(APP_URL . "log/form/", "Ingresar");
        }
        $menu_left_tail->add_menu_item(url::do_url(APP_URL . 'log/out/'), 'Salir', 'nav-logout');
    } else {
        $menu_left_tail->add_menu_item(url::do_url(APP_URL . 'log/form/'), 'Ingresar', 'nav-login');
    }
}
$body->header()->append_div(null, 'k1app-output');
