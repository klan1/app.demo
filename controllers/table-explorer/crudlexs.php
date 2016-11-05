<?php

namespace k1app;

// This might be different on your proyect

use \k1lib\templates\temply as temply;
use \k1lib\urlrewrite\url as url;
use k1app\k1app_template as DOM;

$body = DOM::html()->body();

include temply::load_template("header", APP_TEMPLATE_PATH);
if (!isset($_GET['just-controller'])) {
    include temply::load_template("app-header", APP_TEMPLATE_PATH);
    include temply::load_template("app-footer", APP_TEMPLATE_PATH);
    /**
     * TOP BAR - Tables added to menu
     */
    $db_tables = \k1lib\sql\sql_query($db, "show tables", TRUE);

    $menu_left = DOM::menu_left();
    $auto_app_menu = DOM::menu_left()->add_sub_menu("#", "DB Tables");

    foreach ($db_tables as $row_field => $row_value) {
        $table_to_link = $row_value["Tables_in_" . \k1lib\sql\get_db_database_name($db)];
        $table_alias = \k1lib\db\security\db_table_aliases::encode($table_to_link);

        if (strstr($table_to_link, "view_")) {
            continue;
        }
        $auto_app_menu->add_menu_item(url::do_url("../../{$table_alias}/", [], FALSE), $table_to_link);
    }

    if (strstr($_SERVER['REQUEST_URI'], 'no-rules') === FALSE) {
        $no_follow_rules_url = str_replace("/crudlexs/", "/crudlexs-raw/", $_SERVER['REQUEST_URI']);
        $menu_left->add_menu_item(url::do_url($no_follow_rules_url, ['no-rules' => 1], TRUE), "Don't follow rules");
    } else {
        $follow_rules_url = str_replace("/crudlexs-raw/", "/crudlexs/", $_SERVER['REQUEST_URI']);
        $menu_left->add_menu_item(url::do_url($follow_rules_url, [], TRUE, ['auth-code']), "Follow rules");
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
$controller_object = new \k1lib\crudlexs\controller_base(APP_BASE_URL, $db, $db_table_to_use, "DB Table explorer ($db_table_to_use)", 'k1lib-title-3');
$controller_object->set_config_from_class("\k1app\crudlexs_config");

/**
 * ALL READY, let's do it :)
 */
$div = $controller_object->init_board();

$controller_object->start_board();

// LIST
if ($controller_object->on_object_list()) {
    $controller_object->board_list_object->list_object->apply_link_on_field_filter(
            url::do_url($controller_object->get_controller_root_dir() . "{$controller_object->get_board_read_url_name()}/--rowkeys--/", ["auth-code" => "--authcode--"])
            , (isset($_GET['no-rules']) ? \k1lib\crudlexs\crudlexs_base::USE_KEY_FIELDS : \k1lib\crudlexs\crudlexs_base::USE_LABEL_FIELDS)
    );
}

$controller_object->exec_board();

$controller_object->finish_board();

$body->content()->append_child($div);
