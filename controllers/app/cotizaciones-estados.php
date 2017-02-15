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

DOM::menu_left()->set_active('nav-quotes-states');

$db_table_to_use = "quote_states";
$controller_name = "Estados de Cotización";

/**
 * ONE LINE config: less codign, more party time!
 */
$controller_object = new \k1lib\crudlexs\controller_base(APP_BASE_URL, $db, $db_table_to_use, $controller_name, 'k1lib-title-3');
$controller_object->set_config_from_class("\k1app\quote_state_config");

/**
 * USER LOGIN AS CONSTANT
 */ 
$controller_object->db_table->set_field_constants(["user_login" => session_db::get_user_login()]);

/**
 * ALL READY, let's do it :)
 */
$div = $controller_object->init_board();

// THIS IS ALWAYS NEEDED IF THE CREATE CALL COMES FROM ANOTHER TABLE
$controller_object->read_url_keys_text_for_create('quotes');

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
    
    // quote LINK
    $quote_url = url::do_url(APP_BASE_URL . quotes_config::ROOT_URL . '/' . quotes_config::BOARD_READ_URL . '/--customfieldvalue--/', $get_params);
    $controller_object->object_read()->apply_link_on_field_filter($quote_url, ['quote_id'], ['quote_id']);
    
    // order LINK
    $order_url = url::do_url(APP_BASE_URL . orders_config::ROOT_URL . '/' . orders_config::BOARD_READ_URL . '/--customfieldvalue--/', $get_params);
    $controller_object->object_read()->apply_link_on_field_filter($order_url, ['order_id'], ['order_id']);
}

$controller_object->exec_board();

if ($controller_object->on_object_create()) {
    if ($controller_object->object_create()->get_post_data_catched()) {
        $post_data = $controller_object->object_create()->get_post_data();
        d($post_data);
    }
}

$controller_object->finish_board();

$body->content()->append_child($div);
