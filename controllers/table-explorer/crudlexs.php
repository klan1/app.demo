<?php

namespace k1app;

// This might be different on your proyect

use k1lib\html\template as template;
use \k1lib\urlrewrite\url as url;
use k1app\k1app_template as DOM;

$body = DOM::html()->body();
template::load_template('header');

if (!isset($_GET['just-controller'])) {
    template::load_template('app-header');
    template::load_template('app-footer');

    DOM::menu_left_tail()->set_active('nav-table-explorer');

    /**
     * TOP BAR - Tables added to menu
     */
    $db_tables = \k1lib\sql\sql_query($db, "show tables", TRUE);

    $table_explorer_menu = DOM::menu_left_tail()->add_sub_menu("#", "DB Tables", 'nav-db-table-list', 'nav-table-explorer');

    foreach ($db_tables as $row_field => $row_value) {
        $table_to_link = $row_value["Tables_in_" . \k1lib\sql\get_db_database_name($db)];
        $table_alias = \k1lib\db\security\db_table_aliases::encode($table_to_link);

        if (strstr($table_to_link, "view_")) {
            continue;
        }
        $table_explorer_menu->add_menu_item(url::do_url("../../{$table_alias}/", [], FALSE), $table_to_link, 'nav-' . $table_alias);
    }

    if (strstr($_SERVER['REQUEST_URI'], 'no-rules') === FALSE) {
        $no_follow_rules_url = str_replace("/crudlexs/", "/crudlexs-raw/", $_SERVER['REQUEST_URI']);
        DOM::menu_left_tail()->add_menu_item(url::do_url($no_follow_rules_url, ['no-rules' => 1], TRUE), "Don't follow rules", 'nav-dont-follow-rules', 'nav-table-explorer');
    } else {
        $follow_rules_url = str_replace("/crudlexs-raw/", "/crudlexs/", $_SERVER['REQUEST_URI']);
        DOM::menu_left_tail()->add_menu_item(url::do_url($follow_rules_url, [], TRUE, ['auth-code']), "Follow rules", 'nav-follow-rules', 'nav-table-explorer');
    }
    /**
     * END TOP BAR - Tables added to menu
     */
}

$table_alias = \k1lib\urlrewrite\url::set_url_rewrite_var(\k1lib\urlrewrite\url::get_url_level_count(), "row_key_text", FALSE);
$db_table_to_use = \k1lib\db\security\db_table_aliases::decode($table_alias);
DOM::menu_left_tail()->set_active('nav-' . $table_alias);

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
