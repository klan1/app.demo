<?php

/**
 * CONTROLLER WITH DETAIL LIST - CLIENT SIDE
 * Ver: 1.0
 * Autor: J0hnD03
 * Date: 2016-02-03
 * 
 */

namespace k1app;

use k1lib\templates\temply as temply;
use k1lib\urlrewrite\url as url;
use k1lib\session\session_db as session_db;

use \k1lib\html\DOM as DOM;

$body = DOM::html()->body();

include temply::load_template("header", APP_TEMPLATE_PATH);
include temply::load_template("app-header", APP_TEMPLATE_PATH);
include temply::load_template("app-footer", APP_TEMPLATE_PATH);


/**
 * ONE LINE config: less codign, more party time!
 */
$controller_object = new \k1lib\crudlexs\controller_base(APP_BASE_URL, $db, "to_states", "Task Orders states", $top_bar);
$controller_object->set_config_from_class("\k1app\client_task_orders_states_config");

/**
 * ALL READY, let's do it :)
 */
$div = $controller_object->init_board();
if ($controller_object->on_object_create()) {
    $controller_object->db_table->set_field_constants(["user_login" => \k1lib\session\session_db::get_user_login()]);
}
$task_order_keys_array = [];
$task_order_keys_text = $controller_object->read_url_keys_text_for_create("task_orders", $task_order_keys_array);

$to_keys_text = $controller_object->read_url_keys_text_for_list("task_orders", TRUE);

if ($controller_object->on_board_list()) {
//    $controller_object->board_list_object->set_create_enable(FALSE);
    $controller_object->db_table->set_order_by("to_state_datetime_in", "DESC");
}

$controller_object->start_board();

// LIST
if ($controller_object->on_object_list()) {
    $controller_object->board_list_object->set_apply_field_label_filter(FALSE);
    $controller_object->board_list_object->list_object->apply_link_on_field_filter($controller_object->get_controller_root_dir() . "{$controller_object->get_board_read_url_name()}/--rowkeys--/?auth-code=--authcode--", ['to_state_state']);
}

/**
 * SET DEFAULT value for to_state_state from the last state
 */
if ($controller_object->on_object_create()) {
    $to_info = new \k1lib\crudlexs\class_db_table($db, "view_user_task_orders");
    $to_info->set_query_filter($task_order_keys_array, TRUE);
    $to_info_data = $to_info->get_data(FALSE);
    $controller_object->board_create_object->create_object->set_post_data(['to_state_state' => $to_info_data['to_state']]);
    $controller_object->board_create_object->create_object->put_post_data_on_table_data();
}

$controller_object->exec_board();

if ($controller_object->on_object_create() && $controller_object->board_create_object->create_object->get_inserted_keys()) {

//    $new_keys_array = $controller_object->board_create_object->create_object->get_inserted_keys();
//    $new_keys_text = \k1lib\sql\table_keys_to_text($new_keys_array, $controller_object->db_table->get_db_table_config());
    $new_state_data = $controller_object->board_create_object->create_object->get_inserted_data();
    require 'to-state-new-email.php';
    task_order_state_send_email($db, $task_order_keys_text, $new_state_data);
}

$controller_object->finish_board();
$body->content()->append_child($div);
