<?php

/**
 * CONTROLLER WITH DETAIL LIST - CLIENT SIDE
 * Ver: 1.0
 * Autor: J0hnD03
 * Date: 2016-02-03
 * 
 */

namespace k1app;

use k1lib\html\template as template;
use k1lib\urlrewrite\url as url;
use k1lib\session\session_db as session_db;

use k1app\k1app_template as DOM;

$body = DOM::html()->body();

template::load_template('header');
template::load_template('app-header');
template::load_template('app-footer');

DOM::menu_left()->set_active('nav-clients-menu');
DOM::menu_left()->set_active('nav-clients-task-orders');

/**
 * ONE LINE config: less codign, more party time!
 */
$controller_object = new \k1lib\crudlexs\controller_base(APP_BASE_URL, $db, "to_assignations", "Task Orders Assignations", 'k1lib-title-3');
$controller_object->set_config_from_class("\k1app\client_task_orders_assignations_config");


/**
 * ALL READY, let's do it :)
 */
$div = $controller_object->init_board();
if (!$controller_object->on_board_list()) {
    $controller_object->db_table->set_field_constants(["user_assignator_id" => \k1lib\session\session_db::get_user_login()]);
} else {
    $controller_object->board_list_object->set_create_enable(FALSE);
}

//// LIST
//if ($controller_object->on_board_list()) {
//    $controller_object->board_list_object->set_create_enable(FALSE);
//}

$task_order_keys_text = $controller_object->read_url_keys_text_for_create("task_orders");
$controller_object->read_url_keys_text_for_list("task_orders", TRUE);

$controller_object->start_board();

// LIST
if ($controller_object->on_board_list()) {
    if ($controller_object->on_object_list()) {
        $controller_object->board_list_object->list_object->apply_link_on_field_filter($controller_object->get_controller_root_dir() . "{$controller_object->get_board_read_url_name()}/--rowkeys--/?auth-code=--authcode--", ['to_name']);
    }
}

$controller_object->exec_board();

if ($controller_object->on_object_create() && $controller_object->board_create_object->create_object->get_inserted_keys()) {

//    $new_keys_array = $controller_object->board_create_object->create_object->get_inserted_keys();
//    $new_keys_text = \k1lib\sql\table_keys_to_text($new_keys_array, $controller_object->db_table->get_db_table_config());
    $new_assignation_data = $controller_object->board_create_object->create_object->get_inserted_data();
    require 'to-assignation-new-email.php';
    task_order_assignation_send_email($db, $task_order_keys_text, $new_assignation_data);
}

$controller_object->finish_board();

$body->content()->append_child($div);
