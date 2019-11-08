<?php

namespace k1app;

use \k1lib\session\session_db as k1lib_session;
use \k1lib\urlrewrite\url as url;
use k1app\k1app_template as DOM;

$body = DOM::html()->body();

if (DOM::off_canvas()) {
//    DOM::off_canvas()->left()->set_class('reveal-for-large', TRUE);
//    DOM::off_ca/nvas()->left()->set_attrib('data-options', 'inCanvasFor:large;');
}

if (!isset($_GET['just-controller'])) {

    DOM::set_title(1, K1APP_TITLE);
    DOM::set_title(2, ' :: ');
    DOM::set_title(3, '');

    $menu_left = DOM::menu_left();
    $menu_left_tail = DOM::menu_left_tail();
    if (!empty(DOM::off_canvas())) {

        /**
         * MENU ITEMS
         */
        if (k1lib_session::check_user_level(['god', 'admin'])) {
            $menu_left->add_menu_item(APP_URL . 'app/dashboard/', 'Dashboard', 'nav-dashboard');
        } elseif (k1lib_session::check_user_level(['user'])) {
            $menu_left->add_menu_item(APP_URL . 'app/dashboard/', 'Dashboard', 'nav-dashboard');
        } elseif (k1lib_session::check_user_level(['guest'])) {
            $menu_left->add_menu_item(APP_URL . 'app/dashboard/', 'Dashboard', 'nav-dashboard');
        } else {
            trigger_error("No idea how you do it!", E_USER_ERROR);
        }
        if (k1lib_session::is_logged()) {
            /**
             * APP Preferences
             */
            if (\k1lib\session\session_plain::check_user_level(['god'])) {

                $admin_menu = $menu_left_tail->add_sub_menu('#', 'App preferences', 'nav-app-preferences');

                $admin_menu->add_menu_item(APP_URL . 'table-explorer/show-tables/', 'Table Explorer', 'nav-table-explorer');
                $admin_menu->add_menu_item(APP_URL . 'table-metadata/show-tables/', 'Manage tables', 'nav-manage-tables');
                $admin_menu->add_menu_item(APP_URL . 'table-metadata/load-field-comments/', 'Load fields metadata', 'nav-fields-metadata');
                $admin_menu->add_menu_item(APP_URL . 'table-metadata/export-field-comments/', 'Export field metadata', 'nav-export-fields-meta')->set_attrib('target', '_blank');
            }

            $menu_left_tail->add_menu_item(url::do_url(APP_URL . 'app/log/out/'), 'Logout', 'nav-logout');
        } else {
            $menu_left_tail->add_menu_item(url::do_url(APP_URL . 'app/log/form/'), 'Login', 'nav-login');
        }
    }
}
$body->header()->append_div(null, 'k1app-output');
