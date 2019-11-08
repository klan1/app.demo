<?php

namespace k1app;

use \k1lib\session\session_db as k1lib_session;
use \k1lib\urlrewrite\url as url;
use k1app\k1app_template as DOM;

$body = DOM::html()->body();

if (DOM::off_canvas()) {
//    DOM::off_canvas()->left()->set_class('reveal-for-large', TRUE);
//    DOM::off_canvas()->left()->set_attrib('data-options', 'inCanvasFor:large;');
}

if (!isset($_GET['just-controller'])) {

    DOM::set_title(1, K1APP_TITLE);
    DOM::set_title(2, ' :: ');
    DOM::set_title(3, '');

    $menu_left = DOM::menu_left();
    $menu_left_tail = DOM::menu_left_tail();
    if (!empty(DOM::off_canvas())) {
        $auth_code = '?auth-code=' . md5(\k1lib\K1MAGIC::get_value() . k1lib_session::get_user_login());
        $user_url = APP_URL . users_config::ROOT_URL . '/' . users_config::BOARD_UPDATE_URL . '/' . k1lib_session::get_user_login() . '/' . $auth_code;

        /**
         * MENU ITEMS
         */
        if (k1lib_session::check_user_level(['god', 'admin'])) {
            $menu_left->add_menu_item(APP_URL . 'app/dashboard/', 'Progreso', 'nav-dashboard');

            $menu_left->add_menu_item(APP_URL . 'app/listados/', 'Listados 2019', 'nav-firmas');
            $menu_left->add_menu_item(APP_URL . 'app/cedulas-listado/', 'Cédulas', 'nav-cedulas');

            $censo_menu = $menu_left->add_sub_menu('#', 'Censo 2019', 'nav-camara-2018');
            $censo_menu->add_menu_item(APP_URL . 'censo/consultar-full/', 'Consultar', 'nav-censo-2019');
            $censo_menu->add_menu_item(APP_URL . 'censo/divipole/', 'Ver Divipol', 'nav-censo-divipole');
//            $censo_menu->add_menu_item(APP_URL . 'censo/log/', 'Ver log', 'nav-censo-2019-log');

            $rpt_menu_progress = $menu_left->add_sub_menu('#', 'Reportes de progreso', 'nav-progress-rpt');
            $rpt_menu_progress->add_menu_item(APP_URL . 'app/rpt-progreso-capitanes/', 'Progreso capitanes', 'nav-rpt-progreso-capitanes');
            $rpt_menu_progress->add_menu_item(APP_URL . 'app/rpt-progreso-lideres/', 'Progreso lideres', 'nav-rpt-progreso-lideres');
            $rpt_menu_progress->add_menu_item(APP_URL . 'app/rpt-alertas/', 'Alertas por antecedentes', 'nav-rpt-alertas');
            $rpt_menu_progress->add_menu_item(APP_URL . 'app/rpt-cedulas-duplicadas/', 'Cedulas Duplicadas', 'nav-rpt-duplicadas');
            $rpt_menu_progress->add_menu_item(APP_URL . 'app/rpt-conteo-cedulas-puesto/', 'Cedulas por Puesto', 'nav-rpt-conteo-puesto');
            $rpt_menu_progress->add_menu_item(APP_URL . 'app/rpt-conteo-cedulas-comuna/', 'Cedulas por Comuna', 'nav-rpt-conteo-comuna');
            $rpt_menu_progress->add_menu_item(APP_URL . 'app/rpt-conteo-cedulas-zona/', 'Cedulas por Zona', 'nav-rpt-conteo-zona');

            $rpt_menu_votes = $menu_left->add_sub_menu('#', 'Reportes de votación', 'nav-votes-rpt');
            $rpt_menu_votes->add_menu_item(APP_URL . 'app/rpt-votacion-capitanes/', 'Votación capitanes', 'nav-rpt-votacion-capitanes');
            $rpt_menu_votes->add_menu_item(APP_URL . 'app/rpt-votacion-lideres/', 'Votación lideres', 'nav-rpt-votacion-lideres');
            $rpt_menu_votes->add_menu_item(APP_URL . 'app/rpt-votacion-check-lideres/', 'Chequeo de votación lideres', 'nav-rpt-votacion-check-lideres');


            $config_menu = $menu_left->add_sub_menu('#', 'Configuracion', 'nav-config');
            $config_menu->add_menu_item(APP_URL . 'app/rpt-backup/', 'Descargar DB', 'nav-rpt-backup');
            $config_menu->add_menu_item(APP_URL . 'registraduria/candidatos/', 'Candidatos 2019', 'nav-admin-candidatos');
            $config_menu->add_menu_item(APP_URL . 'app/util-usuarios/', 'Usuarios del app', 'nav-utils-usuarios');
            $config_menu->add_menu_item($user_url, 'Mi perfil', 'nav-utils-perfil');
        } elseif (k1lib_session::check_user_level(['user'])) {
            $menu_left->add_menu_item(APP_URL . 'app/listados/', 'Listados', 'nav-firmas');
            $menu_left->add_menu_item(APP_URL . 'app/cedulas-listado/', 'Todas las cedulas', 'nav-cedulas');
            $config_menu = $menu_left->add_sub_menu('#', 'Configuracion', 'nav-config');
            $config_menu->add_menu_item($user_url, 'Mi perfil', 'nav-utils-perfil');
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
