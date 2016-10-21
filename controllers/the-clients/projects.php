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
use k1lib\session\session_db as session_db;

use \k1lib\html\DOM as DOM;

$body = DOM::html()->body();

include temply::load_template("header", APP_TEMPLATE_PATH);
include temply::load_template("app-header", APP_TEMPLATE_PATH);
include temply::load_template("app-footer", APP_TEMPLATE_PATH);


/**
 * ONE LINE config: less codign, more party time!
 */
$controller_object = new \k1lib\crudlexs\controller_base(APP_BASE_URL, $db, "projects", "Agency projects", $top_bar);
$controller_object->set_config_from_class("\k1app\client_projects_config");
$controller_object->db_table->set_order_by("project_datetime_in", "DESC");

if (session_db::get_user_data()['user_level'] == 'client') {
    $controller_object->db_table->set_query_filter(["client_id" => session_db::get_user_data()['client_id']], TRUE);
    $controller_object->db_table->set_field_constants(["client_id" => session_db::get_user_data()['client_id']]);
} else {
    $controller_object->db_table->set_query_filter(["agency_id" => session_db::get_user_data()['agency_id']], TRUE);
    $controller_object->db_table->set_field_constants(["agency_id" => session_db::get_user_data()['agency_id']]);
}


/**
 * ALL READY, let's do it :)
 */
$div = $controller_object->init_board();

// LIST
if ($controller_object->on_board_list()) {
    $controller_object->board_list_object->set_create_enable(FALSE);
}

$controller_object->read_url_keys_text_for_create("contracts");
$controller_object->read_url_keys_text_for_list("clients", FALSE);

$controller_object->start_board();

// LIST
if ($controller_object->on_board_list()) {
    if ($controller_object->on_object_list()) {
        $controller_object->board_list_object->list_object->apply_link_on_field_filter($controller_object->get_controller_root_dir() . "{$controller_object->get_board_read_url_name()}/--rowkeys--/?auth-code=--authcode--", ['project_name']);
    }
}
// READ
if ($controller_object->on_object_read()) {
    /**
     * Custom Links
     */
    $agency_id = \k1lib\session\session_plain::get_user_data()['agency_id'];

    // Client LINK
    $controller_object->board_read_object->read_object->apply_link_on_field_filter(APP_BASE_URL . client_clients_config::ROOT_URL . '/' . client_clients_config::BOARD_READ_URL . "/--customfieldvalue--/?auth-code=--fieldauthcode--&back-url=" . urlencode($_SERVER['REQUEST_URI']), ['client_id'], "--fieldvalue----$agency_id");

    // Contract LINK
    $controller_object->board_read_object->read_object->apply_link_on_field_filter(APP_BASE_URL . client_contracts_config::ROOT_URL . '/' . client_contracts_config::BOARD_READ_URL . "/--customfieldvalue--/?auth-code=--fieldauthcode--&back-url=" . urlencode($_SERVER['REQUEST_URI']), ['contract_id'], ['contract_id', 'client_id', 'agency_id']);
}

$controller_object->exec_board();

$controller_object->finish_board();


if ($controller_object->on_board_read()) {
    $related_div = $div->append_div("row k1lib-crudlexs-related-data");

    $sub_title = new \k1lib\html\h3("Task Orders");
    $sub_title->set_attrib("style", "margin-top:1em;");
    $sub_title->append_to($related_div);

    $task_orders_db_table = new \k1lib\crudlexs\class_db_table($db, "task_orders");
    $custom_sql = "SELECT 
            view_user_task_orders.to_id to_id, 
            view_user_task_orders.to_name Task,
            view_user_task_orders.to_priority, 
            view_user_task_orders.to_state Estate, 
            view_user_task_orders.to_delivery_date Deliver, 
            view_user_task_orders.to_delivery_time Hour,
            view_user_task_orders.user_login User
        FROM view_user_task_orders";
    $task_orders_db_table->set_custom_sql_query($custom_sql, FALSE);
    $task_orders_db_table->set_order_by('Deliver', 'DESC');

    if ($task_orders_db_table->get_state()) {
        $task_orders_db_table->set_query_filter(['to_state' => 'unread'], TRUE, FALSE);
        $controller_object->board_read_object->set_related_show_all_data(FALSE);
        $controller_object->board_read_object->set_related_show_new(TRUE);
        $users_subdetail_div = $controller_object->board_read_object->create_related_list($task_orders_db_table, ["Task"], "New", client_task_orders_config::ROOT_URL, client_task_orders_config::BOARD_CREATE_URL, client_task_orders_config::BOARD_READ_URL, client_task_orders_config::BOARD_LIST_URL);
        $users_subdetail_div->append_to($related_div);
    }
    if ($task_orders_db_table->get_state()) {
        $task_orders_db_table->clear_query_filter();
        $task_orders_db_table->set_query_filter(['to_state' => 'read'], TRUE, FALSE);
        $controller_object->board_read_object->set_related_show_all_data(FALSE);
        $controller_object->board_read_object->set_related_show_new(FALSE);
        $users_subdetail_div = $controller_object->board_read_object->create_related_list($task_orders_db_table, ["Task"], "Read", client_task_orders_config::ROOT_URL, client_task_orders_config::BOARD_CREATE_URL, client_task_orders_config::BOARD_READ_URL, client_task_orders_config::BOARD_LIST_URL);
        $users_subdetail_div->append_to($related_div);
    }
    if ($task_orders_db_table->get_state()) {
        $task_orders_db_table->clear_query_filter();
        $task_orders_db_table->set_query_filter(['to_state' => 'working'], TRUE, FALSE);
        $controller_object->board_read_object->set_related_show_all_data(FALSE);
        $controller_object->board_read_object->set_related_show_new(FALSE);
        $users_subdetail_div = $controller_object->board_read_object->create_related_list($task_orders_db_table, ["Task"], "Working", client_task_orders_config::ROOT_URL, client_task_orders_config::BOARD_CREATE_URL, client_task_orders_config::BOARD_READ_URL, client_task_orders_config::BOARD_LIST_URL);
        $users_subdetail_div->append_to($related_div);
    }
    if ($task_orders_db_table->get_state()) {
        $task_orders_db_table->clear_query_filter();
        $task_orders_db_table->set_query_filter(['to_state' => 'finished'], TRUE, FALSE);
        $controller_object->board_read_object->set_related_show_all_data(FALSE);
        $controller_object->board_read_object->set_related_show_new(FALSE);
        $users_subdetail_div = $controller_object->board_read_object->create_related_list($task_orders_db_table, ["Task"], "Finished", client_task_orders_config::ROOT_URL, client_task_orders_config::BOARD_CREATE_URL, client_task_orders_config::BOARD_READ_URL, client_task_orders_config::BOARD_LIST_URL);
        $users_subdetail_div->append_to($related_div);
    }
    if ($task_orders_db_table->get_state()) {
        $task_orders_db_table->clear_query_filter();
        $task_orders_db_table->set_query_filter(['to_state' => 'delivered'], TRUE, FALSE);
        $controller_object->board_read_object->set_related_show_all_data(FALSE);
        $controller_object->board_read_object->set_related_show_new(FALSE);
        $users_subdetail_div = $controller_object->board_read_object->create_related_list($task_orders_db_table, ["Task"], "On delivered", client_task_orders_config::ROOT_URL, client_task_orders_config::BOARD_CREATE_URL, client_task_orders_config::BOARD_READ_URL, client_task_orders_config::BOARD_LIST_URL);
        $users_subdetail_div->append_to($related_div);
    }
    if ($task_orders_db_table->get_state()) {
        $task_orders_db_table->clear_query_filter();
        $task_orders_db_table->set_query_filter(['to_state' => 'modifications'], TRUE, FALSE);
        $controller_object->board_read_object->set_related_show_all_data(FALSE);
        $controller_object->board_read_object->set_related_show_new(FALSE);
        $users_subdetail_div = $controller_object->board_read_object->create_related_list($task_orders_db_table, ["Task"], "On modifications", client_task_orders_config::ROOT_URL, client_task_orders_config::BOARD_CREATE_URL, client_task_orders_config::BOARD_READ_URL, client_task_orders_config::BOARD_LIST_URL);
        $users_subdetail_div->append_to($related_div);
    }
    if ($task_orders_db_table->get_state()) {
        $task_orders_db_table->clear_query_filter();
        $task_orders_db_table->set_query_filter(['to_state' => 'closed'], TRUE, FALSE);
        $controller_object->board_read_object->set_related_show_all_data(FALSE);
        $controller_object->board_read_object->set_related_show_new(FALSE);
        $users_subdetail_div = $controller_object->board_read_object->create_related_list($task_orders_db_table, ["Task"], "Closed", client_task_orders_config::ROOT_URL, client_task_orders_config::BOARD_CREATE_URL, client_task_orders_config::BOARD_READ_URL, client_task_orders_config::BOARD_LIST_URL);
        $users_subdetail_div->append_to($related_div);
    }
}
$body->content()->append_child($div);
