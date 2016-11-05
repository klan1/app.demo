<?php

namespace k1app;

use \k1lib\session\session_db as k1lib_session;
use \k1lib\urlrewrite\url as url;
use k1app\k1app_template as DOM;

$body = DOM::html()->body();
if (!isset($_GET['just-controller'])) {

    DOM::set_title(1, APP_TITLE);
    DOM::set_title(2, " :: ");
    DOM::set_title(3, "HOME");

    $left_menu = DOM::off_canvas()->left_menu();
    $left_menu_tail = DOM::off_canvas()->left_menu_tail();

    if (k1lib_session::is_logged()) {
        /**
         * APP CONTROLLERS
         */
//        $li_warehouse = $left_menu->add_menu_item("#", "Bodegas");
//        $sub_menu = $left_menu->add_sub_menu($li_warehouse);
        $left_menu->add_menu_item(APP_URL . "app/tablero/", "Inicio");
        $left_menu->add_menu_item(APP_URL . "app/productos/", "Productos");
        $inventarios_menu = $left_menu->add_sub_menu("#", "Inventario");
        $inventarios_menu->add_menu_item(APP_URL . "app/bodega-inventario/", "Inventario presente");
        $inventarios_menu->add_menu_item(APP_URL . "app/bodega-inventario/?modo=pasado", "Historial");
        $inventarios_menu->add_menu_item(APP_URL . "app/bodega-inventario/?modo=sin-ubicar", "Por ubicar");

        /**
         * APP Preferences
         */
        if (\k1lib\session\session_plain::check_user_level(['god'])) {

            $admin_menu = $left_menu_tail->add_sub_menu('#', "App preferences", "separator");

            $admin_menu->add_menu_item(url::do_url(APP_URL . "app/usuarios/"), "Usuarios del App", $sub_menu);
            $admin_menu->add_menu_item(APP_URL . "app/bodegas/", "Bodegas", $sub_menu);
            $admin_menu->add_menu_item(APP_URL . "table-metadata/show-tables/", "Manage tables");
            $admin_menu->add_menu_item(APP_URL . "table-metadata/export-field-comments/", "Export field comments")->set_attrib("target", "_blank");
            $admin_menu->add_menu_item(APP_URL . "table-metadata/load-field-comments/", "Load field comments");
        }

        $left_menu_tail->add_menu_item(url::do_url(APP_URL . "log/out/"), "Salir");
    } else {
        $left_menu_tail->add_menu_item(url::do_url(APP_URL . "log/form/"), "Ingresar");
    }
}
$body->header()->append_div(null, "k1app-output");