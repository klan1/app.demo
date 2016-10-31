<?php

namespace k1app;

// This might be different on your proyect

use \k1lib\templates\temply as temply;
use \k1lib\urlrewrite\url as url;
use \k1lib\html\DOM as DOM;
use k1lib\session\session_db as session_db;

$body = DOM::html()->body();

include temply::load_template("header", APP_TEMPLATE_PATH);
include temply::load_template("app-header", APP_TEMPLATE_PATH);
include temply::load_template("app-footer", APP_TEMPLATE_PATH);

$db_table_to_use = "product_position";
$controller_name = "Inventario de bodega";

/**
 * ONE LINE config: less codign, more party time!
 */
$controller_object = new \k1lib\crudlexs\controller_base(APP_BASE_URL, $db, $db_table_to_use, $controller_name, $top_bar);
$controller_object->set_config_from_class("\k1app\warehouses_inventory_config");

/**
 * USER LOGIN AS CONSTANT
 */
$controller_object->db_table->set_field_constants(["user_login" => session_db::get_user_login()]);

/**
 * ALL READY, let's do it :)
 */
$div = $controller_object->init_board();

// CREATE - UPDATE
IF ($controller_object->on_board_create() || $controller_object->on_board_update()) {
    \k1lib\crudlexs\input_helper::set_fk_fields_to_skip(['warehouse_id', 'wh_column_id', 'wh_column_row_id', 'wh_position_id']);
}

$controller_object->start_board();

// LIST
if ($controller_object->on_object_list()) {
    $read_url = url::do_url($controller_object->get_controller_root_dir() . "{$controller_object->get_board_read_url_name()}/--rowkeys--/", ["auth-code" => "--authcode--"]);
    $controller_object->board_list_object->list_object->apply_link_on_field_filter($read_url, \k1lib\crudlexs\crudlexs_base::USE_LABEL_FIELDS);
}

$controller_object->exec_board();

$controller_object->finish_board();

//if ($controller_object->on_board_read()) {
//    $related_div = $div->append_div("row k1lib-crudlexs-related-data");
//    /**
//     * Related list
//     */
//    $related_db_table = new \k1lib\crudlexs\class_db_table($db, "wh_columns");
//    $controller_object->board_read_object->set_related_show_all_data(FALSE);
//    $related_div = $controller_object->board_read_object->create_related_list($related_db_table, NULL, "Columnas", warehouse_columns_config::ROOT_URL, warehouse_columns_config::BOARD_CREATE_URL, warehouse_columns_config::BOARD_READ_URL, warehouse_columns_config::BOARD_LIST_URL, TRUE);
//    $related_div->append_to($related_div);
//}


$body->content()->append_child($div);
