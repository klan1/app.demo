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

template::load_template('header');
template::load_template('app-header');
template::load_template('app-footer');


/**
 * ONE LINE config: less codign, more party time!
 */
$controller_object = new \k1lib\crudlexs\controller_base(APP_BASE_URL, $db, "task_orders", "Agency task orders", $top_bar);
$controller_object->set_config_from_class("\k1app\client_task_orders_config");

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

//            view_user_task_orders.to_delivery_time 'Delivery time',

    $custom_sql = "SELECT 
            view_user_task_orders.to_id to_id, 
            view_user_task_orders.to_name,
            view_user_task_orders.to_priority Priority, 
            view_user_task_orders.to_state State, 
            view_user_task_orders.to_delivery_date 'Delivery date', 
            view_user_task_orders.user_login User,
            view_user_task_orders.project_id,
            view_user_task_orders.contract_id,
            view_user_task_orders.client_id
        FROM view_user_task_orders";
    $controller_object->db_table->set_custom_sql_query($custom_sql, FALSE);
    $controller_object->db_table->set_query_filter_exclude(['to_state' => 'closed'], TRUE, FALSE);
}

$controller_object->read_url_keys_text_for_create("projects");

$controller_object->start_board();

if ($controller_object->on_object_read()) {
    /**
     * Custom Links
     */
    $agency_id = \k1lib\session\session_plain::get_user_data()['agency_id'];

    // Project LINK
    $controller_object->board_read_object->read_object->apply_link_on_field_filter(APP_BASE_URL . client_projects_config::ROOT_URL . '/' . client_projects_config::BOARD_READ_URL . "/--customfieldvalue--/?auth-code=--fieldauthcode--&back-url=" . urlencode($_SERVER['REQUEST_URI']), ['project_id'], ['project_id', 'contract_id', 'client_id', 'agency_id']);

    // Client LINK
    $controller_object->board_read_object->read_object->apply_link_on_field_filter(APP_BASE_URL . client_clients_config::ROOT_URL . '/' . client_clients_config::BOARD_READ_URL . "/--customfieldvalue--/?auth-code=--fieldauthcode--&back-url=" . urlencode($_SERVER['REQUEST_URI']), ['client_id'], "--fieldvalue----$agency_id");

    // Contract LINK
    $controller_object->board_read_object->read_object->apply_link_on_field_filter(APP_BASE_URL . client_contracts_config::ROOT_URL . '/' . client_contracts_config::BOARD_READ_URL . "/--customfieldvalue--/?auth-code=--fieldauthcode--&back-url=" . urlencode($_SERVER['REQUEST_URI']), ['contract_id'], ['contract_id', 'client_id', 'agency_id']);

    /**
     * MAKE READ THE UNREAD FOR THE OWNER
     */
    $custom_sql = "SELECT * FROM view_user_task_orders";
    $to_full_info = new \k1lib\crudlexs\class_db_table($db, $controller_object->db_table->get_db_table_name());
    $to_full_info->set_query_filter(
            [
                'user_login' => session_db::get_user_login(),
                'to_id' => $controller_object->board_read_object->read_object->get_row_keys_text(),
            ]
    );
    $to_full_info->set_query_limit(0, 1);
    $to_full_info->set_custom_sql_query($custom_sql, FALSE);
    $this_to_info = $to_full_info->get_data(FALSE);
    if ($this_to_info['user_login'] == session_db::get_user_login() && $this_to_info['to_state'] == "unread") {
        /**
         * PUT STATE: READ
         */
        $new_state = [
            'to_id' => $controller_object->board_read_object->read_object->get_row_keys_text(),
            'user_login' => session_db::get_user_login(),
            'to_state_state' => 'read',
            'to_state_note' => 'Message opened by the user assigned to this Task Order',
        ];
        \k1lib\sql\sql_insert($db, "to_states", $new_state);
        /**
         * SEND the read notification
         */
        require 'to-state-new-email.php';
        task_order_state_send_email($db, $controller_object->board_read_object->read_object->get_row_keys_text(), $new_state, TRUE);
    }
}

// LIST
if ($controller_object->on_board_list()) {
    if ($controller_object->on_object_list()) {
        $agency_id = \k1lib\session\session_plain::get_user_data()['agency_id'];

        // Row ID LINK
        $controller_object->board_list_object->list_object->apply_link_on_field_filter($controller_object->get_controller_root_dir() . "{$controller_object->get_board_read_url_name()}/--rowkeys--/?auth-code=--authcode--", ['to_name', 'to_id']);

        // Project LINK
        $controller_object->board_list_object->list_object->apply_link_on_field_filter(APP_BASE_URL . client_projects_config::ROOT_URL . '/' . client_projects_config::BOARD_READ_URL . "/--customfieldvalue--/?auth-code=--fieldauthcode--&back-url=" . urlencode($_SERVER['REQUEST_URI']), ['project_id'], ['project_id', 'contract_id', 'client_id', 'agency_id'] );

        // Client LINK
        $controller_object->board_list_object->list_object->apply_link_on_field_filter(APP_BASE_URL . client_clients_config::ROOT_URL . '/' . client_clients_config::BOARD_READ_URL . "/--customfieldvalue--/?auth-code=--fieldauthcode--&back-url=" . urlencode($_SERVER['REQUEST_URI']), ['client_id'], "--fieldvalue----$agency_id" );

        // Contract LINK
        $controller_object->board_list_object->list_object->apply_link_on_field_filter(APP_BASE_URL . client_contracts_config::ROOT_URL . '/' . client_contracts_config::BOARD_READ_URL . "/--customfieldvalue--/?auth-code=--fieldauthcode--&back-url=" . urlencode($_SERVER['REQUEST_URI']), ['contract_id'], ['contract_id', 'client_id', 'agency_id'] );



//        $controller_object->board_list_object->set_apply_field_label_filter(true);
    }
}

$controller_object->exec_board();
$controller_object->finish_board();

if ($controller_object->on_board_read()) {
    $related_div1 = $div->append_div("row k1lib-crudlexs-related-data");
    $related_div2 = $div->append_div("row k1lib-crudlexs-related-data");

    $assignations_db_table = new \k1lib\crudlexs\class_db_table($db, "to_assignations");
    $assignations_db_table->set_order_by("to_assignation_datetime_in", "DESC");
    $controller_object->board_read_object->set_related_show_all_data(FALSE);
    $assignations_div = $controller_object->board_read_object->create_related_list($assignations_db_table, ["to_assignation_priority"], "Assigantions", client_task_orders_assignations_config::ROOT_URL, client_task_orders_assignations_config::BOARD_CREATE_URL, client_task_orders_assignations_config::BOARD_READ_URL, client_task_orders_assignations_config::BOARD_LIST_URL, TRUE);
    $assignations_div->set_attrib("class", "column large-6 medium-12 small-12", TRUE)->append_to($related_div1);

    $states_db_table = new \k1lib\crudlexs\class_db_table($db, "to_states");
    $states_db_table->set_order_by("to_state_datetime_in", "DESC");
    $controller_object->board_read_object->set_related_show_all_data(TRUE);
    $states_div = $controller_object->board_read_object->create_related_list($states_db_table, ["to_state_state"], "States and messages", client_task_orders_states_config::ROOT_URL, client_task_orders_states_config::BOARD_CREATE_URL, client_task_orders_states_config::BOARD_READ_URL, client_task_orders_states_config::BOARD_LIST_URL, TRUE);
    $states_div->set_attrib("class", "column large-12", TRUE)->append_to($related_div2);
}

$body->content()->append_child($div);
