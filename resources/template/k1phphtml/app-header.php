<?php

namespace k1app;

use \k1lib\session\session_db as k1lib_session;
use \k1lib\urlrewrite\url as url;
use \k1lib\html\DOM as DOM;

$body = DOM::html()->body();
if (!isset($_GET['just-controller'])) {

    $body_header = $body->header();

    $top_bar = new \k1lib\html\foundation\top_bar($body_header);

    $top_bar->set_title(1, APP_TITLE);
    $top_bar->set_title(2, " :: ");
    $top_bar->set_title(3, "HOME");

    $menu_right = $top_bar->menu_right();

    /**
     * AUTO APP
     */
    $li = $top_bar->add_menu_item(url::do_url(APP_URL . "table-explorer/show-tables/"), "Home");
    $li->set_id("table-explorer-menu");

    if (k1lib_session::is_logged()) {
        /**
         * APP Preferences
         */
        if (\k1lib\session\session_plain::check_user_level(['god'])) {

            $li = $menu_right->append_li();
            $li->append_a("#", "App preferences");

            $sub_menu = $top_bar->add_sub_menu($li);
            $top_bar->add_menu_item(APP_URL . "table-metadata/show-tables/", "Manage tables", $sub_menu);
            $li = $top_bar->add_menu_item(APP_URL . "table-metadata/export-field-comments/", "Export field comments", $sub_menu);
            $li->get_child(0)->set_attrib("target", "_blank");
            $top_bar->add_menu_item(APP_URL . "table-metadata/load-field-comments/", "Load field comments", $sub_menu);
        }

        $top_bar->add_button(url::do_url(APP_URL . "log/out/"), "Salir", "alert");
    } else {
        $top_bar->add_button(url::do_url(APP_URL . "log/form/"), "Ingresar");
    }
}
$body->header()->append_div(null, "k1app-output");
