<?php

namespace k1app;

use \k1lib\templates\temply as temply;
use \k1lib\session\session_db as k1lib_session;
use \k1lib\urlrewrite\url as url;
use \k1lib\session\session_plain as session_plain;
use \k1lib\html\DOM as DOM;

$body = DOM::html()->body();

$body_header = $body->header();

$top_bar = new \k1lib\html\foundation\top_bar($body_header);
$top_bar->append_to($body_header);

$top_bar->set_title(1, APP_TITLE);
$top_bar->set_title(2, " :: ");
$top_bar->set_title(3, "HOME");

$top_bar->add_menu_item(url::do_url(APP_URL . "auto-app/show-tables/"), "Auto App");

if (k1lib_session::is_logged()) {
    if (\k1lib\session\session_plain::check_user_level(['god'])) {
        $li = $top_bar->add_menu_item("#", "App preferences");
        $sub_menu = $top_bar->add_sub_menu($li);
        $top_bar->add_menu_item(url::do_url(APP_URL . "db-table-manager/show-tables/"), "Manage tables", $sub_menu);
        $top_bar->add_menu_item(url::do_url(APP_URL . "db-table-manager/export-field-comments/"), "Export field comments", $sub_menu)->set_attrib("target", "_blank");
        $top_bar->add_menu_item(url::do_url(APP_URL . "db-table-manager/load-field-comments/"), "Load field comments", $sub_menu);
    }

    $top_bar->add_button("#", "View PHP Code", "warning","php-viewer-button")->set_attrib("target", "php-viewer");
    $top_bar->add_button(url::do_url(APP_URL . "log/out/"), "Salir", "alert");
} else {
    $top_bar->add_button(url::do_url(APP_URL . "log/form/"), "Ingresar");
}

$body->header()->append_div()->set_value(temply::set_template_place("controller-msg"));
$body->content()->append_child(new \k1lib\html\h3(temply::set_template_place("board-name")));
