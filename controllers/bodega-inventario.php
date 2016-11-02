<?php

namespace k1app;

// This might be different on your proyect

use \k1lib\templates\temply as temply;
use \k1lib\urlrewrite\url as url;
use \k1lib\html\DOM as DOM;
use k1lib\session\session_db as session_db;

$body = DOM::html()->body();
$content = DOM::html()->body()->content();

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

$incoming = \k1lib\forms\check_all_incomming_vars($_GET);

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
    if (isset($incoming['modo'])) {
        if ($incoming['modo'] == 'pasado') {
            $controller_object->db_table->set_query_filter_exclude(['product_exit' => NULL], TRUE);
        } elseif ($incoming['modo'] == 'sin-ubicar') {
            $filter = [
                'wh_column_id' => NULL,
                'wh_column_row_id' => NULL,
                'wh_column_row_position_id' => NULL,
            ];
            $controller_object->db_table->set_query_filter($filter, TRUE);
        } else {
            \k1lib\html\html_header_go(url::do_url($_SERVER['REQUEST_URI'], [], FALSE));
        }
    } else {
        $filter = [
            'product_exit' => NULL,
        ];
        $filter_exclude = [
            'wh_column_id' => NULL,
            'wh_column_row_id' => NULL,
            'wh_column_row_position_id' => NULL,
        ];
        $controller_object->db_table->set_query_filter($filter, TRUE);
        $controller_object->db_table->set_query_filter_exclude($filter_exclude, TRUE);
    }
}

// CREATE - UPDATE
if ($controller_object->on_board_create() || $controller_object->on_board_update()) {
    \k1lib\crudlexs\input_helper::set_fk_fields_to_skip(['warehouse_id', 'wh_column_id', 'wh_column_row_id', 'wh_position_id']);
}
// LIST
if ($controller_object->on_object_list()) {
    $controller_object->board_list_object->set_create_enable(FALSE);
}

/**
 * START
 */
$controller_object->start_board();

// LIST
if ($controller_object->on_object_list()) {
    if (isset($incoming['modo']) && $incoming['modo'] == 'pasado') {
        $content->append_h3("Inventario pasado");
//        $create_positions_button = new \k1lib\html\a(url::do_url($_SERVER['REQUEST_URI'], [], FALSE), "Ver inventario presente", NULL, "button success");
    } else if (isset($incoming['modo']) && $incoming['modo'] == 'sin-ubicar') {
        $content->append_h3("Inventario sin ubicar");
//        $create_positions_button = new \k1lib\html\a(url::do_url($_SERVER['REQUEST_URI'], [], FALSE), "Ver inventario presente", NULL, "button");
    } else {
        $content->append_h3("Inventario presente");
//        $create_positions_button = new \k1lib\html\a($_SERVER['REQUEST_URI'] . "?modo=pasado", "Ver inventario pasado", NULL, "button warning");
    }
    $total_weight = $controller_object->db_table->get_field_operation("product_weight", "SUM");
    if (!empty($total_weight)) {
        $content->append_h5("Peso total: {$total_weight}");
    }
//    $create_positions_button->append_to($controller_object->board_list_object->button_div_tag());

    $read_url = url::do_url($controller_object->get_controller_root_dir() . "{$controller_object->get_board_update_url_name()}/--rowkeys--/", ["auth-code" => "--authcode--", "back-url" => $_SERVER['REQUEST_URI']]);
    $controller_object->board_list_object->list_object->apply_link_on_field_filter($read_url, ['product_position_cod']);
}
// UPDATE
if ($controller_object->on_board_update()) {
    if (isset($_POST)) {
        $encoded_field_name = $controller_object->board_update_object->update_object->encrypt_field_name('wh_column_id');
        if (isset($_POST[$encoded_field_name])) {
            $_POST[$encoded_field_name] = strtoupper($_POST[$encoded_field_name]);
        }
    }
}

/**
 * EXEC
 */
$controller_object->exec_board();

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

$body->content()->append_child($div);
