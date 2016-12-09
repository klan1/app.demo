<?php

namespace k1app;

// This might be different on your proyect

use k1lib\html\template as template;
use \k1lib\urlrewrite\url as url;
use k1app\k1app_template as DOM;
use k1lib\session\session_db as session_db;

$body = DOM::html()->body();
$content = DOM::html()->body()->content();

template::load_template('header');
template::load_template('app-header');
template::load_template('app-footer');

DOM::menu_left()->set_active('nav-inventory');

$db_table_to_use = "product_position";
$controller_name = "Inventario de bodega";

/**
 * ONE LINE config: less codign, more party time!
 */
$controller_object = new \k1lib\crudlexs\controller_base(APP_BASE_URL, $db, $db_table_to_use, $controller_name, 'k1lib-title-3');
$controller_object->set_config_from_class("\k1app\warehouses_inventory_config");


$incoming = \k1lib\forms\check_all_incomming_vars($_GET);

/**
 * CUSTOM SQL QUERY
 */
if (isset($incoming['modo'])) {
    if ($incoming['modo'] == 'pasado') {
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
    } elseif ($incoming['modo'] == 'sin-ubicar') {
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
                . 'product_datetime_in'
                . ' FROM view_inventory_in';
    }
} else {
    $custom_sql = 'SELECT '
            . 'product_position_id,'
            . 'product_position_cod,'
            . 'warehouse_id,'
            . 'wh_column_id,'
            . 'wh_column_row_id,'
            . 'wh_position_id,'
            . 'product_name,'
            . 'product_weight,'
            . 'product_weight_left,'
            . 'product_quantity,'
            . 'product_quantity_left,'
            . 'user_login,'
            . 'product_valid,'
            . 'product_datetime_in'
            . ' FROM view_inventory_in';
}

$custom_field_labels = [
    'product_name' => 'PRODUCTO',
    'product_weight_left' => 'QUEDAN(K)',
    'product_quantity_left' => 'QUEDAN'
];

/**
 * USER LOGIN AS CONSTANT
 */
$controller_object->db_table->set_field_constants(["user_login" => session_db::get_user_login()]);

/**
 * INIT
 */
$div = $controller_object->init_board();
$controller_object->read_url_keys_text_for_create("products");
$controller_object->read_url_keys_text_for_list("products", FALSE);

/**
 * MODE CONTROL ON LIST
 */
if ($controller_object->on_board_list()) {
    // NO CREATE
    $controller_object->board_list_object->set_create_enable(FALSE);
    // CUSTOM SQL FOR VIEW USAGE
    $controller_object->db_table->set_custom_sql_query($custom_sql);
    $controller_object->object_list()->set_custom_field_labels($custom_field_labels);
    // LISTING TYPE AS 'MODO'
    if (isset($incoming['modo'])) {
        if ($incoming['modo'] == 'pasado') {
            DOM::menu_left()->set_active('nav-inventory-past');

            $filter = [
                'product_weight_left' => 0,
                'product_quantity_left' => 0,
            ];
            $controller_object->db_table->set_query_filter($filter, TRUE, FALSE);

//            $controller_object->db_table->set_order_by('product_datetime_in', 'DESC');
        } elseif ($incoming['modo'] == 'sin-ubicar') {
            DOM::menu_left()->set_active('nav-inventory-nonplaced');

            $filter = [
                'wh_column_id' => NULL,
                'wh_column_row_id' => NULL,
                'wh_position_id' => NULL,
            ];
            $controller_object->db_table->set_query_filter($filter, TRUE, FALSE);

//            $controller_object->db_table->set_order_by('product_datetime_in', 'ASC');
        } else {
            \k1lib\html\html_header_go(url::do_url($_SERVER['REQUEST_URI'], [], FALSE));
        }
    } else {
        DOM::menu_left()->set_active('nav-inventory-present');

        $filter = [
        ];

        $filter_exclude = [
            'product_weight_left' => 0,
            'product_quantity_left' => 0,
            'wh_column_id' => NULL,
            'wh_column_row_id' => NULL,
            'wh_position_id' => NULL,
        ];
        $controller_object->db_table->set_query_filter($filter, TRUE, FALSE);
        $controller_object->db_table->set_query_filter_exclude($filter_exclude, TRUE, FALSE);

//        $controller_object->db_table->set_order_by('product_datetime_in', 'DESC');
    }
}
// LIST
if ($controller_object->on_board_read()) {
    // CUSTOM SQL FOR VIEW USAGE
    $controller_object->db_table->set_custom_sql_query($custom_sql);
    $controller_object->object_read()->set_custom_field_labels($custom_field_labels);
    $controller_object->object_read()->set_fields_to_hide(['product_position_id']);
}
// CREATE - UPDATE
if ($controller_object->on_board_create() || $controller_object->on_board_update()) {
    \k1lib\crudlexs\input_helper::set_fk_fields_to_skip(['warehouse_id', 'wh_column_id', 'wh_column_row_id', 'wh_position_id']);
}


/**
 * START
 */
$controller_object->start_board();

if ($controller_object->on_board_read()) {
    /**
     * DO FULL OUT
     */
    $read_data = $controller_object->object_read()->get_db_table_data()[1];
    $read_data['product_weight_left'] = round($read_data['product_weight_left'], 1);
    if (isset($_GET['do-full-out']) && ($_GET['do-full-out'] == '1')) {
        $out_data = [
            'product_position_id' => $read_data['product_position_id'],
            'product_weight' => $read_data['product_weight_left'],
            'product_quantity' => $read_data['product_quantity_left'],
            'user_login' => session_db::get_user_login(),
        ];
        if (\k1lib\sql\sql_insert($db, 'product_position_out', $out_data)) {
            \k1lib\notifications\on_DOM::queue_mesasage('Fue retirado todo el invetario presente.');
            unset($_GET['do-full-out']);
            $return_url = url::do_url('./');
            \k1lib\html\html_header_go($return_url);
        }
    }
}
// LIST
if ($controller_object->on_object_list()) {

    /**
     * link on table
     */
    $read_url = url::do_url($controller_object->get_controller_root_dir() . "{$controller_object->get_board_read_url_name()}/--rowkeys--/", ["auth-code" => "--authcode--", "back-url" => $_SERVER['REQUEST_URI']]);
    $edit_url = url::do_url($controller_object->get_controller_root_dir() . "{$controller_object->get_board_update_url_name()}/--rowkeys--/", ["auth-code" => "--authcode--", "back-url" => $_SERVER['REQUEST_URI']]);

    if (isset($incoming['modo']) && $incoming['modo'] == 'pasado') {
        $content->append_h3("Inventario pasado");
        $controller_object->board_list_object->list_object->apply_link_on_field_filter($read_url, ['product_position_cod']);
    } else if (isset($incoming['modo']) && $incoming['modo'] == 'sin-ubicar') {
        $content->append_h3("Inventario sin ubicar");
        $controller_object->board_list_object->list_object->apply_link_on_field_filter($edit_url, ['product_position_cod']);
    } else {
        $content->append_h3("Inventario presente");
        $controller_object->board_list_object->list_object->apply_link_on_field_filter($read_url, ['product_position_cod']);
    }
    $total_weight = round($controller_object->db_table->get_field_operation("product_weight_left", "SUM"), 1);
    $total_quantity = $controller_object->db_table->get_field_operation("product_quantity_left", "SUM");
    if (!empty($total_weight)) {
        $content->append_h5("{$total_weight} Kg en {$total_quantity} unidades");
    }
//    $create_positions_button->append_to($controller_object->board_list_object->button_div_tag());
}
// UPDATE
if ($controller_object->on_board_update()) {
    if ($controller_object->object_update()->get_post_data_catched()) {
        $post_data = $controller_object->object_update()->get_post_data();
        $original_data = $controller_object->object_update()->get_db_table_data()[1];
        // COL UPPERCASE HACK
        if (isset($post_data['wh_column_id'])) {
            $controller_object->object_update()->set_post_incomming_value('wh_column_id', strtoupper($post_data['wh_column_id']));
        }

        // FREE WAREHOUSE STORAGE SLOT CHECK
        if (
                !empty($post_data['warehouse_id']) &&
                !empty($post_data['wh_column_id']) &&
                !empty($post_data['wh_column_row_id']) &&
                !empty($post_data['wh_position_id'])) {
            //Check only if has changed
            if (
                    ($post_data['warehouse_id'] != $original_data['warehouse_id']) ||
                    ($post_data['wh_column_id'] != $original_data['wh_column_id']) ||
                    ($post_data['wh_column_row_id'] != $original_data['wh_column_row_id']) ||
                    ($post_data['wh_position_id'] != $original_data['wh_position_id'])
            ) {
                $sql_check_free = "SELECT * FROM view_wh_positions_busy WHERE "
                        . "warehouse_id = {$post_data['warehouse_id']} AND "
                        . "wh_column_id = '{$post_data['wh_column_id']}' AND "
                        . "wh_column_row_id = {$post_data['wh_column_row_id']} AND "
                        . "wh_position_id = {$post_data['wh_position_id']}";
                if (\k1lib\sql\sql_query($db, $sql_check_free)) {
                    $controller_object->object_update()->set_post_validation_errors(['wh_position_id' => 'La posicion esta ocupada']);
                }
            }
        }
    }
}
/**
 * EXEC
 */
$controller_object->exec_board();

// LIST
if ($controller_object->on_board_list()) {
    $html_table = $controller_object->board_list_object->list_object->get_html_table();
    if (!empty($html_table)) {
        if (isset($incoming['modo']) && $incoming['modo'] == 'sin-ubicar') {
            $html_table->hide_fields(['product_position_id', 'wh_column_id', 'wh_column_row_id', 'wh_position_id']);
        } else {
            $html_table->hide_fields(['product_position_id']);
        }
    }
}

if ($controller_object->on_board_create()) {
    $new_keys = $controller_object->board_create_object->create_object->get_inserted_keys();
    if (!empty($new_keys)) {
        $n36 = \k1lib\utils\decimal_to_n36($new_keys['product_position_id']);
        $controller_object->db_table->update_data(['product_position_cod' => $n36], $new_keys);
    }
}

/**
 * FINISH
 */
$controller_object->finish_board();

if ($controller_object->on_board_read()) {
    $related_div = $div->append_div("row k1lib-crudlexs-related-data");

    if (($read_data['product_weight_left'] + 0) > 0) {
        $controller_object->board_read_object->set_related_show_new(TRUE);

        $full_out_link = url::do_url($_SERVER['REQUEST_URI'], ['do-full-out' => 1]);
        $button_make_full_out = new \k1lib\html\a($full_out_link, ' Salida completa', null, 'button fi-alert');
        $out_confirmation_message = "¿Esta seguro que desea dar salida a {$read_data['product_weight_left']} kg en {$read_data['product_quantity_left']} cajas?";
        $button_make_full_out->set_attrib('onClick', "return confirm('{$out_confirmation_message}')");
        $button_make_full_out->append_to($related_div);
    } else {
        $controller_object->board_read_object->set_related_show_new(FALSE);
    }

    $inventory = new \k1lib\crudlexs\class_db_table($db, "product_position_out");
    $inventory->set_custom_sql_query('SELECT '
            . 'product_position_id,'
            . 'product_position_out_id,'
            . 'product_name AS PRODUCTO,'
            . 'product_weight_out AS `SALE(K)`,'
            . 'product_weight_left AS `QUEDA(K)`,'
            . 'product_quantity_out AS SALE,'
            . 'product_quantity_left AS QUEDA,'
            . 'product_datetime_out AS `FECHA SALIDA`'
            . ' FROM view_inventory_out');
    $inventory->set_order_by('product_datetime_out', 'DESC');

    $controller_object->board_read_object->set_related_rows_to_show(50);
    $controller_object->board_read_object->set_related_show_all_data(FALSE);
    $related_list = $controller_object->board_read_object->create_related_list($inventory, [], "SALIDAS DE PRODUCTO", warehouses_inventory_out_config::ROOT_URL, warehouses_inventory_out_config::BOARD_CREATE_URL, warehouses_inventory_out_config::BOARD_UPDATE_URL, warehouses_inventory_out_config::BOARD_LIST_URL, TRUE);
    $related_list->append_to($related_div);
}

$body->content()->append_child($div);
