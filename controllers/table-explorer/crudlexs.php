<?php

namespace k1app;

// This might be different on your proyect

use \k1lib\templates\temply as temply;
use \k1lib\urlrewrite\url as url;
use \k1lib\html\DOM as DOM;

$body = DOM::html()->body();

include temply::load_template("header", APP_TEMPLATE_PATH);
if (!isset($_GET['just-controller'])) {
    include temply::load_template("app-header", APP_TEMPLATE_PATH);
    include temply::load_template("app-footer", APP_TEMPLATE_PATH);
    /**
     * TOP BAR - Tables added to menu
     */
    $db_tables = \k1lib\sql\sql_query($db, "show tables", TRUE);

    $ul = new \k1lib\html\ul();

    $li_auto_app_menu = DOM::html()->body()->header()->get_element_by_id("table-explorer-menu");
    if (empty($li_auto_app_menu)) {
        $li_auto_app_menu = $top_bar->add_menu_item("#", "DB Tables");
    }
    if (!isset($top_bar)) {
        $top_bar = new \k1lib\html\foundation\top_bar(null);
    }
    $sub_menu = $top_bar->add_sub_menu($li_auto_app_menu);
    foreach ($db_tables as $row_field => $row_value) {
        $table_to_link = $row_value["Tables_in_" . \k1lib\sql\get_db_database_name($db)];
        $table_alias = \k1lib\db\security\db_table_aliases::encode($table_to_link);

        if (strstr($table_to_link, "view_")) {
            continue;
        }
        $top_bar->add_menu_item(url::do_url("../../{$table_alias}/", [], FALSE), $table_to_link, $sub_menu);
    }

    if (strstr($_SERVER['REQUEST_URI'], 'no-rules') === FALSE) {
        $no_follow_rules_url = str_replace("/crudlexs/", "/crudlexs-raw/", $_SERVER['REQUEST_URI']);
        $top_bar->add_menu_item(url::do_url($no_follow_rules_url, ['no-rules' => 1], TRUE), "Don't follow rules");
    } else {
        $follow_rules_url = str_replace("/crudlexs-raw/", "/crudlexs/", $_SERVER['REQUEST_URI']);
        $top_bar->add_menu_item(url::do_url($follow_rules_url, [], TRUE, ['auth-code']), "Follow rules");
    }
    /**
     * END TOP BAR - Tables added to menu
     */
}

$table_alias = \k1lib\urlrewrite\url::set_url_rewrite_var(\k1lib\urlrewrite\url::get_url_level_count(), "row_key_text", FALSE);
$db_table_to_use = \k1lib\db\security\db_table_aliases::decode($table_alias);

/**
 * ONE LINE config: less codign, more party time!
 */
$controller_object = new \k1lib\crudlexs\controller_base(APP_BASE_URL, $db, $db_table_to_use, "DB Table explorer ($db_table_to_use)", $top_bar);
$controller_object->set_config_from_class("\k1app\crudlexs_config");
$controller_object->set_security_no_rules_enable(TRUE);

/**
 * ALL READY, let's do it :)
 */
$div = $controller_object->init_board();

// CREATE
if (isset($controller_object->board_create_object)) {
    if (isset($_GET['no-rules']) && $_GET['no-rules'] == "1") {
        $controller_object->board_create_object->set_show_rule_to_apply(NULL);
        $controller_object->board_create_object->set_apply_label_filter(FALSE);
        $controller_object->board_create_object->set_apply_field_label_filter(FALSE);
    }
}
// READ
if ($controller_object->on_board_read()) {
    if (isset($_GET['no-rules']) && $_GET['no-rules'] == "1") {
        $controller_object->board_read_object->set_show_rule_to_apply(NULL);
        $controller_object->board_read_object->set_apply_label_filter(FALSE);
        $controller_object->board_read_object->set_apply_field_label_filter(FALSE);
        $controller_object->board_read_object->set_use_label_as_title_enabled(FALSE);
    }
}
// UPDATE
if (isset($controller_object->board_update_object)) {
    if (isset($_GET['no-rules']) && $_GET['no-rules'] == "1") {
        $controller_object->board_update_object->set_show_rule_to_apply(NULL);
        $controller_object->board_update_object->set_apply_label_filter(FALSE);
        $controller_object->board_update_object->set_apply_field_label_filter(FALSE);
    }
}
// DELETE
if (isset($controller_object->board_delete_object)) {
    if (isset($_GET['no-rules']) && $_GET['no-rules'] == "1") {
        $controller_object->board_delete_object->set_show_rule_to_apply(NULL);
    }
}
// LIST
if ($controller_object->on_board_list()) {
    if (isset($_GET['no-rules']) && $_GET['no-rules'] == "1") {
        $controller_object->board_list_object->set_show_rule_to_apply(NULL);
        $controller_object->board_list_object->set_apply_label_filter(FALSE);
        $controller_object->board_list_object->set_apply_field_label_filter(FALSE);
    }
}

$controller_object->start_board();

// LIST
if ($controller_object->on_board_list()) {
    if ($controller_object->on_object_list()) {
        $controller_object->board_list_object->list_object->apply_link_on_field_filter(
                url::do_url($controller_object->get_controller_root_dir() . "{$controller_object->get_board_read_url_name()}/--rowkeys--/", ["auth-code" => "--authcode--"])
                , (isset($_GET['no-rules']) ? \k1lib\crudlexs\crudlexs_base::USE_KEY_FIELDS : \k1lib\crudlexs\crudlexs_base::USE_LABEL_FIELDS)
        );
    }
}

$controller_object->exec_board();

$controller_object->finish_board();

$body->content()->append_child($div);
