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


    /**
     * Non placed inventory
     */
    $inventory = new \k1lib\crudlexs\class_db_table($db, "product_position");
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

    $html_table = $controller_object->board_read()->get_related_html_table_object();
    if (!empty($html_table)) {
        $html_table->hide_fields(['wh_column_id', 'wh_column_row_id', 'wh_position_id', 'user_login', 'product_valid', 'product_datetime_in']);
    }

    $related_list->append_to($related_div);


    /**
     * Present inventory
     */
    $inventory = new \k1lib\crudlexs\class_db_table($db, "product_position");
    $custom_sql = 'SELECT '
            . 'product_position_id,'
            . 'product_position_cod,'
            . 'warehouse_id,'
            . 'wh_column_id,'
            . 'wh_column_row_id,'
            . 'wh_position_id,'
            . 'product_weight,'
            . 'product_weight_left AS `QUEDA(K)`,'
            . 'product_quantity,'
            . 'product_quantity_left AS QUEDA,'
            . 'user_login,'
            . 'product_valid,'
            . 'product_datetime_in'
            . ' FROM view_inventory_in';

    $inventory->set_custom_sql_query($custom_sql);

    $filter_exclude = [
        'product_weight_left' => 0,
        'product_quantity_left' => 0,
        'wh_column_id' => NULL,
        'wh_column_row_id' => NULL,
        'wh_position_id' => NULL,
    ];
    $inventory->set_query_filter_exclude($filter_exclude, TRUE, FALSE);
    $controller_object->board_read_object->set_related_show_all_data(FALSE);
    $controller_object->board_read_object->set_related_show_new(FALSE);

    $related_list = $controller_object->board_read_object->create_related_list($inventory, ['product_position_cod'], "Inventario presente", warehouses_inventory_config::ROOT_URL, warehouses_inventory_config::BOARD_CREATE_URL, warehouses_inventory_config::BOARD_READ_URL, warehouses_inventory_config::BOARD_LIST_URL, TRUE);

    $html_table = $controller_object->board_read()->get_related_html_table_object();
    if (!empty($html_table)) {
        $html_table->hide_fields(['product_position_id']);
    }
    $related_list->append_to($related_div);
    //Show total
    $total_weight = $inventory->get_field_operation("product_weight_left", "SUM");
    $total_quantity = $inventory->get_field_operation("product_quantity_left", "SUM");
    if (!empty($total_weight)) {
        $related_messaje_div = $related_list->get_elements_by_class('related-messaje');
        $related_messaje_div[0]->append_h5("{$total_weight} Kg en {$total_quantity} unidades");
    }


    /**
     * Past inventory
     */
    $inventory = new \k1lib\crudlexs\class_db_table($db, "product_position");
    $custom_sql = 'SELECT '
            . 'product_position_id,'
            . 'product_position_cod,'
            . 'warehouse_id,'
            . 'wh_column_id,'
            . 'wh_column_row_id,'
            . 'wh_position_id,'
            . 'product_name,'
            . 'product_weight,'
            . 'product_quantity,'
            . 'user_login,'
            . 'product_valid,'
            . 'product_datetime_in'
            . ' FROM view_inventory_in';
    $inventory->set_custom_sql_query($custom_sql);

    $filter = [
        'product_weight_left' => 0,
        'product_quantity_left' => 0,
    ];
    $inventory->set_query_filter($filter, TRUE, FALSE);

    $controller_object->board_read_object->set_related_rows_to_show(10);
    $controller_object->board_read_object->set_related_show_all_data(FALSE);
    $controller_object->board_read_object->set_related_show_new(FALSE);
    $related_list = $controller_object->board_read_object->create_related_list($inventory, ['product_position_cod'], "Inventario pasado", warehouses_inventory_config::ROOT_URL, warehouses_inventory_config::BOARD_CREATE_URL, warehouses_inventory_config::BOARD_READ_URL, warehouses_inventory_config::BOARD_LIST_URL, TRUE);
    $html_table = $controller_object->board_read()->get_related_html_table_object();
    if (!empty($html_table)) {
        $html_table->hide_fields(['product_position_id']);
    }
    $related_list->append_to($related_div);
}

$body->content()->append_child($div);
