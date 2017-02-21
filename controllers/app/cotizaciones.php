<?php

namespace k1app;
// This might be different on your proyect

use k1lib\html\template as template;
use \k1lib\urlrewrite\url as url;
use k1app\k1app_template as DOM;
use k1lib\session\session_db as session_db;

\k1lib\session\session_db::is_logged(TRUE, APP_URL . 'log/form/');
$body = DOM::html()->body();

template::load_template('header');
template::load_template('app-header');
template::load_template('app-footer');

DOM::menu_left()->set_active('nav-quotes');

$db_table_to_use = "quotes";
$controller_name = "Cotizaciones";

/**
 * ONE LINE config: less codign, more party time!
 */
$controller_object = new \k1lib\crudlexs\controller_base(APP_BASE_URL, $db, $db_table_to_use, $controller_name, 'k1lib-title-3');
$controller_object->set_config_from_class("\k1app\quotes_config");
//
///**
// * USER LOGIN AS CONSTANT
// */
//$controller_object->db_table->set_field_constants(["user_login" => session_db::get_user_login()]);

/**
 * ALL READY, let's do it :)
 */
$related_keys_array = [];

$div = $controller_object->init_board();

// THIS IS ALWAYS NEEDED IF THE CREATE CALL COMES FROM ANOTHER TABLE
$controller_object->read_url_keys_text_for_create('orders', $related_keys_array);

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
    
    // order LINK
    $order_url = url::do_url(APP_BASE_URL . orders_config::ROOT_URL . '/' . orders_config::BOARD_READ_URL . '/--customfieldvalue--/', $get_params);
    $controller_object->object_read()->apply_link_on_field_filter($order_url, ['order_id'], ['order_id']);
}

$controller_object->exec_board();

if ($controller_object->on_object_create()) {
    
    $order_table = new \k1lib\crudlexs\class_db_table($db, 'orders');
    $order_state_update['order_state'] = 'en cotización';
    $order_table->update_data($order_state_update, ['order_id' => $related_keys_array['order_id']]);
        
}

$controller_object->finish_board();

if ($controller_object->on_board_read()) {
    $related_div = $div->append_div("row k1lib-crudlexs-related-data");
     /**
     * Related list
     */
    $related_db_table = new \k1lib\crudlexs\class_db_table($db, "quote_states");
    $controller_object->board_read_object->set_related_show_all_data(FALSE);
    $related_list = $controller_object->board_read_object->create_related_list($related_db_table, NULL, "Estado de Cotización", quote_state_config::ROOT_URL, quote_state_config::BOARD_CREATE_URL, quote_state_config::BOARD_READ_URL, quote_state_config::BOARD_LIST_URL, TRUE);
    $related_list->append_to($related_div);
    /**
     * Related list
     */
    $related_db_table = new \k1lib\crudlexs\class_db_table($db, "quote_details");
    $controller_object->board_read_object->set_related_show_all_data(FALSE);
    $related_list = $controller_object->board_read_object->create_related_list($related_db_table, NULL, "Presentaciones de Cotización", quote_details_config::ROOT_URL, quote_details_config::BOARD_CREATE_URL, quote_details_config::BOARD_READ_URL, quote_details_config::BOARD_LIST_URL, TRUE);
    $related_list->append_to($related_div);

}

$body->content()->append_child($div);
