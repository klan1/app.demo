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
    DOM::set_title(2, '::');

    $menu_left = DOM::menu_left();
    $menu_left_tail = DOM::menu_left_tail();

    if (k1lib_session::is_logged()) {
        /**
         * APP CONTROLLERS
         */
//        $li_warehouse = $menu_left->add_menu_item("#", "Bodegas");
//        $sub_menu = $menu_left->add_sub_menu($li_warehouse);
        $menu_left->add_menu_item(APP_URL . "app/tablero/", "Inicio", 'nav-index');
        $menu_left->add_menu_item(APP_URL . "app/productos/", "Productos", 'nav-products');
        $inventarios_menu = $menu_left->add_sub_menu("#", "Inventario", 'nav-inventory');
        $inventarios_menu->add_menu_item(APP_URL . "app/bodega-inventario/?modo=sin-ubicar", "Por ubicar", 'nav-inventory-nonplaced');
        $inventarios_menu->add_menu_item(APP_URL . "app/bodega-inventario/", "Inventario presente", 'nav-inventory-present');
        $inventarios_menu->add_menu_item(APP_URL . "app/bodega-inventario/?modo=pasado", "Historial", 'nav-inventory-past');
        $menu_left->add_menu_item(url::do_url(APP_URL . "app/usuarios/"), "Usuarios del App", 'nav-app-users');
        $menu_left->add_menu_item(APP_URL . "app/bodegas/", "Configurar Bodegas", 'nav-configure-warehouses');

        /**
         * APP Preferences
         */
        if (\k1lib\session\session_plain::check_user_level(['god'])) {

            $admin_menu = $menu_left_tail->add_sub_menu('#', 'App preferences', 'nav-app-preferences');
            $admin_menu->add_menu_item(APP_URL . 'table-explorer/show-tables/', 'Table Explorer', 'nav-table-explorer');
            $admin_menu->add_menu_item(APP_URL . 'table-metadata/show-tables/', 'Manage tables', 'nav-manage-tables');
            $admin_menu->add_menu_item(APP_URL . 'table-metadata/export-field-comments/', 'Export field metadata', 'nav-export-fields-meta')->set_attrib('target', '_blank');
            $admin_menu->add_menu_item(APP_URL . 'table-metadata/load-field-comments/', 'Load fields metadata', 'nav-fields-metadata');
        }

        $menu_left_tail->add_menu_item(url::do_url(APP_URL . 'log/out/'), 'Salir', 'nav-logout');
    } else {
        $menu_left_tail->add_menu_item(url::do_url(APP_URL . 'log/form/'), 'Ingresar', 'nav-login');
    }
}
$body->header()->append_div(null, 'k1app-output');
