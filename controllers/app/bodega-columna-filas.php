<?php

namespace k1app;

// This might be different on your proyect

use k1lib\html\template as template;
use \k1lib\urlrewrite\url as url;
use k1app\k1app_template as DOM;

$body = DOM::html()->body();

template::load_template('header');
template::load_template('app-header');
template::load_template('app-footer');

$db_table_to_use = "wh_column_rows";
$controller_name = "Filas de la Columna de la bodega";

/**
 * ONE LINE config: less codign, more party time!
 */
$controller_object = new \k1lib\crudlexs\controller_base(APP_BASE_URL, $db, $db_table_to_use, $controller_name, 'k1lib-title-3');
$controller_object->set_config_from_class("\k1app\warehouse_columns_row_config");

/**
 * ALL READY, let's do it :)
 */
$div = $controller_object->init_board();

$controller_object->read_url_keys_text_for_create("wh_columns");
$controller_object->read_url_keys_text_for_list("wh_columns", TRUE);

$controller_object->start_board();

// LIST
if ($controller_object->on_object_list()) {
    $read_url = url::do_url($controller_object->get_controller_root_dir() . "{$controller_object->get_board_read_url_name()}/--rowkeys--/", ["auth-code" => "--authcode--"]);
    $controller_object->board_list_object->list_object->apply_link_on_field_filter($read_url, \k1lib\crudlexs\crudlexs_base::USE_LABEL_FIELDS);
}

if ($controller_object->on_object_read()) {
    /**
     * Custom Links
     */
    $get_params = [
        'auth-code' => '--fieldauthcode--',
        'back-url' => $_SERVER['REQUEST_URI']
    ];
    
    $warehouse_url = url::do_url(APP_BASE_URL . warehouses_config::ROOT_URL . '/' . warehouses_config::BOARD_READ_URL . '/--customfieldvalue--/', $get_params);
    $controller_object->object_read()->apply_link_on_field_filter($warehouse_url, ['warehouse_id'], ['warehouse_id']);
    
    $wh_columns_url = url::do_url(APP_BASE_URL . warehouse_columns_config::ROOT_URL . '/' . warehouse_columns_config::BOARD_READ_URL . '/--customfieldvalue--/', $get_params);
    $controller_object->object_read()->apply_link_on_field_filter($wh_columns_url, ['wh_column_id'], ['wh_column_id']);
    
}

$controller_object->exec_board();

$controller_object->finish_board();

if ($controller_object->on_board_read()) {
    $related_div = $div->append_div("row k1lib-crudlexs-related-data");
    /**
     * Related list
     */
    $related_db_table = new \k1lib\crudlexs\class_db_table($db, "wh_positions");
    $controller_object->board_read_object->set_related_show_all_data(FALSE);
    $related_list = $controller_object->board_read_object->create_related_list($related_db_table, NULL, "Pisos", warehouse_positions_config::ROOT_URL, warehouse_positions_config::BOARD_CREATE_URL, warehouse_positions_config::BOARD_READ_URL, warehouse_positions_config::BOARD_LIST_URL, TRUE);
    $related_list->append_to($related_div);
}

$body->content()->append_child($div);
