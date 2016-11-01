<?php

namespace k1app;

// This might be different on your proyect

use \k1lib\templates\temply as temply;
use \k1lib\urlrewrite\url as url;
use \k1lib\html\DOM as DOM;

$body = DOM::html()->body();

include temply::load_template("header", APP_TEMPLATE_PATH);
include temply::load_template("app-header", APP_TEMPLATE_PATH);
include temply::load_template("app-footer", APP_TEMPLATE_PATH);

$db_table_to_use = "warehouses";
$controller_name = "Bodegas";

/**
 * ONE LINE config: less codign, more party time!
 */
$controller_object = new \k1lib\crudlexs\controller_base(APP_BASE_URL, $db, $db_table_to_use, $controller_name, $top_bar);
$controller_object->set_config_from_class("\k1app\warehouses_config");

/**
 * ALL READY, let's do it :)
 */
$div = $controller_object->init_board();

$controller_object->start_board();

// LIST
if ($controller_object->on_object_list()) {
    $read_url = url::do_url($controller_object->get_controller_root_dir() . "{$controller_object->get_board_read_url_name()}/--rowkeys--/", ["auth-code" => "--authcode--"]);
    $controller_object->board_list_object->list_object->apply_link_on_field_filter($read_url, \k1lib\crudlexs\crudlexs_base::USE_LABEL_FIELDS);
}

$controller_object->exec_board();

$controller_object->finish_board();

if ($controller_object->on_board_read()) {
    $create_positions_button = new \k1lib\html\a(APP_URL . "/bodegas-hacer-posiciones/", "Crear posiciones", "_SELF", "button small warning");
    $create_positions_button->append_to($controller_object->board_read_object->button_div_tag());
    
    $related_div = $div->append_div("row k1lib-crudlexs-related-data");
    /**
     * Related list
     */
    $related_db_table = new \k1lib\crudlexs\class_db_table($db, "wh_columns");
    $controller_object->board_read_object->set_related_show_all_data(FALSE);
    $related_list = $controller_object->board_read_object->create_related_list($related_db_table, NULL, "Columnas", warehouse_columns_config::ROOT_URL, warehouse_columns_config::BOARD_CREATE_URL, warehouse_columns_config::BOARD_READ_URL, warehouse_columns_config::BOARD_LIST_URL, TRUE);
    $related_list->append_to($related_div);
}


$body->content()->append_child($div);
