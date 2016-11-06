<?php

namespace k1app;

// This might be different on your proyect

use \k1lib\templates\temply as temply;
use \k1lib\urlrewrite\url as url;
use k1app\k1app_template as DOM;

$body = DOM::html()->body();

include temply::load_template("header", APP_TEMPLATE_PATH);
include temply::load_template("app-header", APP_TEMPLATE_PATH);
include temply::load_template("app-footer", APP_TEMPLATE_PATH);

DOM::menu_left()->set_active('nav-products');

$db_table_to_use = "products";
$controller_name = "Productos";

/**
 * ONE LINE config: less codign, more party time!
 */
$controller_object = new \k1lib\crudlexs\controller_base(APP_BASE_URL, $db, $db_table_to_use, $controller_name, 'k1lib-title-3');
$controller_object->set_config_from_class("\k1app\products_config");

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
    $related_div = $div->append_div("row k1lib-crudlexs-related-data");
    $inventory = new \k1lib\crudlexs\class_db_table($db, "product_position");
    /**
     * Non placed inventory
     */
    $filter = [
        'wh_column_id' => NULL,
        'wh_column_row_id' => NULL,
        'wh_column_row_position_id' => NULL,
    ];
    $inventory->set_query_filter($filter, TRUE);
    $controller_object->board_read_object->set_related_rows_to_show(50);
    $controller_object->board_read_object->set_related_show_all_data(FALSE);
    $controller_object->board_read_object->set_related_show_new(TRUE);
    $related_list = $controller_object->board_read_object->create_related_list($inventory, ['product_position_cod'], "Inventario por ubicar", warehouses_inventory_config::ROOT_URL, warehouses_inventory_config::BOARD_CREATE_URL, warehouses_inventory_config::BOARD_UPDATE_URL, warehouses_inventory_config::BOARD_LIST_URL, TRUE);
    $related_list->append_to($related_div);
    /**
     * Present inventory
     */
    $inventory->clear_query_filter();
    $filter = [
        'product_exit' => NULL,
    ];
    $filter_exclude = [
        'wh_column_id' => NULL,
        'wh_column_row_id' => NULL,
        'wh_column_row_position_id' => NULL,
    ];
    $inventory->set_query_filter($filter, TRUE);
    $inventory->set_query_filter_exclude($filter_exclude, TRUE);
    $controller_object->board_read_object->set_related_show_all_data(FALSE);
    $controller_object->board_read_object->set_related_show_new(FALSE);
    $related_list = $controller_object->board_read_object->create_related_list($inventory, ['product_position_cod'], "Inventario presente", warehouses_inventory_config::ROOT_URL, warehouses_inventory_config::BOARD_CREATE_URL, warehouses_inventory_config::BOARD_UPDATE_URL, warehouses_inventory_config::BOARD_LIST_URL, TRUE);
    $related_list->append_to($related_div);
    //Show total
    $total_weight = $inventory->get_field_operation("product_weight", "SUM");
    if (!empty($total_weight)) {
        $related_messaje_div = $related_list->get_elements_by_class('related-messaje');
        $related_messaje_div[0]->append_h5("Peso total: {$total_weight}");
    }

//    $total_inventory = "SELECT "
    /**
     * Past inventory
     */
    $inventory->clear_query_filter();
    $inventory->set_query_filter_exclude(['product_exit' => NULL], TRUE);
    $controller_object->board_read_object->set_related_rows_to_show(10);
    $controller_object->board_read_object->set_related_show_all_data(FALSE);
    $controller_object->board_read_object->set_related_show_new(FALSE);
    $related_list = $controller_object->board_read_object->create_related_list($inventory, ['product_position_cod'], "Inventario pasado", warehouses_inventory_config::ROOT_URL, warehouses_inventory_config::BOARD_CREATE_URL, warehouses_inventory_config::BOARD_UPDATE_URL, warehouses_inventory_config::BOARD_LIST_URL, TRUE);
    $related_list->append_to($related_div);
}

$body->content()->append_child($div);
