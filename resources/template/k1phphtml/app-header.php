<?php

namespace k1app;

use \k1lib\session\session_db as session_db;
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
    if (!empty(DOM::off_canvas())) {

        /**
         * AUTO APP
         */
        if (session_db::is_logged()) {
            /**
             * CLIENT USER
             */
            if (\k1lib\session\session_db::check_user_level(['client'])) {
                $auth_code = '?auth-code=' . md5(\k1lib\K1MAGIC::get_value() . session_db::get_user_data()['client_id'] . '--' . session_db::get_user_data()['agency_id']);
                $client_url = APP_URL . client_clients_config::ROOT_URL . '/' . client_clients_config::BOARD_READ_URL . '/' . session_db::get_user_data()['client_id'] . '--' . session_db::get_user_data()['agency_id'] . '/' . $auth_code;

                $client_menu = $menu_left->add_sub_menu('#', session_db::get_user_login(), 'nav-client-user');
                $client_menu->add_menu_item($client_url, 'My business', $sub_menu);
            }
            if (\k1lib\session\session_db::check_user_level(['god', 'admin', 'user'])) {
                /**
                 * USER PROFILE
                 */
                $auth_code = '?auth-code=' . md5(\k1lib\K1MAGIC::get_value() . session_db::get_user_login());
                $user_url = APP_URL . agency_users_config::ROOT_URL . '/' . agency_users_config::BOARD_READ_URL . '/' . session_db::get_user_login() . '/' . $auth_code;
                $menu_left->add_menu_item($user_url, session_db::get_user_login(), 'nav-my-profile');

                /**
                 * THE AGENCY
                 */
                $agency_menu = $menu_left->add_sub_menu('#', 'Agency', 'nav-agency-menu');
                if (\k1lib\session\session_db::check_user_level(['god', 'admin'])) {
                    $agency_menu->add_menu_item(APP_URL . 'the-agency/my-agency/', 'My Agency', 'nav-agency-my');
                    $agency_menu->add_menu_item(APP_URL . 'the-agency/locations/', 'Locations', 'nav-agency-locations');
                    $agency_menu->add_menu_item(APP_URL . 'the-agency/departments/', 'Departments', 'nav-agency-departaments');
                    $agency_menu->add_menu_item(APP_URL . 'the-agency/job-titles/', 'Jobs Titles', 'nav-agency-job-titles');
                }
                $menu_left->add_menu_item(APP_URL . 'the-agency/users/', 'Users', 'nav-agency-users');
            }
            /**
             * THE CLIENTS
             */
            $clients_menu = $menu_left->add_sub_menu('#', 'Clients', 'nav-clients-menu');
            $clients_menu->add_menu_item(APP_URL . 'the-clients/clients/', 'View clients', 'nav-clients');
            if (\k1lib\session\session_db::check_user_level(['god', 'admin'])) {
                $clients_menu->add_menu_item(APP_URL . 'the-clients/contacts/', 'Clients contacts', 'nav-clients-contacts');
                $clients_menu->add_menu_item(APP_URL . 'the-clients/contracts/', 'Contracts', 'nav-clients-contracts');
                $clients_menu->add_menu_item(APP_URL . 'the-clients/projects/', 'Projects', 'nav-clients-projects');
            }
            /**
             * TASK ORDERS
             */
            if (\k1lib\session\session_db::check_user_level(['god', 'admin'])) {

                $menu_left->add_menu_item(APP_URL . 'the-clients/task-orders/', 'Task orders', 'nav-clients-task-orders');
            }

            /**
             * AUTO APP
             */
            if (\k1lib\session\session_plain::check_user_level(['god'])) {

                $admin_menu->add_menu_item(APP_URL . 'table-explorer/show-tables/', 'Table Explorer', 'nav-table-explorer');
                $admin_menu->add_menu_item(APP_URL . 'table-metadata/show-tables/', 'Manage tables', 'nav-manage-tables');
                $admin_menu->add_menu_item(APP_URL . 'table-metadata/load-field-comments/', 'Load fields metadata', 'nav-fields-metadata');
                $admin_menu->add_menu_item(APP_URL . 'table-metadata/export-field-comments/', 'Export field metadata', 'nav-export-fields-meta')->set_attrib('target', '_blank');
            }

            $menu_left_tail->add_menu_item(url::do_url(APP_URL . 'log/out/'), 'Salir', 'nav-logout');
        } else {
            $menu_left_tail->add_menu_item(url::do_url(APP_URL . 'log/form/'), 'Ingresar', 'nav-login');
        }
    }
}
$body->header()->append_div(null, 'k1app-output');
