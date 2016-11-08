<?php

namespace k1app;

// This might be different on your proyect

use \k1lib\templates\temply as temply;
use \k1lib\urlrewrite\url as url;
use k1app\k1app_template as DOM;
use k1lib\session\session_db as session_db;

$body = DOM::html()->body();
$content = DOM::html()->body()->content();

include temply::load_template("header", APP_TEMPLATE_PATH);
include temply::load_template("app-header", APP_TEMPLATE_PATH);
include temply::load_template("app-footer", APP_TEMPLATE_PATH);

DOM::menu_left()->set_active('nav-inventory');

$db_table_to_use = "product_position_out";
$controller_name = "Inventario de bodega";

/**
 * ONE LINE config: less codign, more party time!
 */
$controller_object = new \k1lib\crudlexs\controller_base(APP_BASE_URL, $db, $db_table_to_use, $controller_name, 'k1lib-title-3');
$controller_object->set_config_from_class("\k1app\warehouses_inventory_out_config");

$incoming = \k1lib\forms\check_all_incomming_vars($_GET);

/**
 * USER LOGIN AS CONSTANT
 */
$controller_object->db_table->set_field_constants(["user_login" => session_db::get_user_login()]);

/**
 * INIT
 */
$div = $controller_object->init_board();
$controller_object->read_url_keys_text_for_create("product_position");
$controller_object->read_url_keys_text_for_list("product_position", FALSE);

// LIST
if ($controller_object->on_object_list()) {
    $controller_object->board_list_object->set_create_enable(FALSE);
}

/**
 * START
 */
$controller_object->start_board();

/**
 * EXEC
 */
$controller_object->exec_board();

/**
 * FINISH
 */
$controller_object->finish_board();

if ($controller_object->on_board_read()) {
    $related_div = $div->append_div("row k1lib-crudlexs-related-data");
    $inventory = new \k1lib\crudlexs\class_db_table($db, "product_position_out");
    $controller_object->board_read_object->set_related_rows_to_show(50);
    $controller_object->board_read_object->set_related_show_all_data(FALSE);
    $controller_object->board_read_object->set_related_show_new(TRUE);
    $related_list = $controller_object->board_read_object->create_related_list($inventory, ['product_position_out'], "SALIDAS DE PRODUCTO", warehouses_inventory_config::ROOT_URL, warehouses_inventory_config::BOARD_CREATE_URL, warehouses_inventory_config::BOARD_UPDATE_URL, warehouses_inventory_config::BOARD_LIST_URL, TRUE);
    $related_list->append_to($related_div);
    
}

$body->content()->append_child($div);
