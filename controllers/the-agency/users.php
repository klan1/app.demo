<?php

/**
 * CONTROLLER WITH DETAIL LIST AND URL READ
 * Ver: 1.0
 * Autor: J0hnD03
 * Date: 2016-02-03
 * 
 */

namespace k1app;

use k1lib\html\template as template;
use k1lib\session\session_db as session_db;
use k1app\k1app_template as DOM;

$body = DOM::html()->body();

template::load_template('header');
template::load_template('app-header');
template::load_template('app-footer');

/**
 * ONE LINE config: less codign, more party time!
 */
$controller_object = new \k1lib\crudlexs\controller_base(APP_BASE_URL, $db, "users", "Agency users", $top_bar);
$controller_object->set_config_from_class("\k1app\agency_users_config");

$controller_object->db_table->set_query_filter(["agency_id" => session_db::get_user_data()['agency_id']], TRUE);
$controller_object->db_table->set_field_constants(["agency_id" => session_db::get_user_data()['agency_id']]);

$controller_object->db_table->set_order_by("location_id", 'ASC');
$controller_object->db_table->set_order_by("dep_id", 'ASC');
$controller_object->db_table->set_order_by("job_title_id", 'ASC');

/**
 * ALL READY, let's do it :)
 */
$div = $controller_object->init_board();

// LIST
if ($controller_object->on_board_list()) {
    $controller_object->board_list_object->set_create_enable(FALSE);
}
if (isset($controller_object->board_create_object)) {
    $controller_object->read_url_keys_text_for_create("locations");
}
if ($controller_object->on_board_read()) {
    if (!session_db::check_user_level(['god', 'admin'])) {
        if ($controller_object->board_read_object->read_object->get_row_keys_array()['user_login'] !== session_db::get_user_login()) {
            $controller_object->board_read_object->set_update_enable(FALSE);
        }
    }
//    $controller_object->board_read_object->read_object->set_read_custom_template(APP_VIEWS_PATH . '/read-templates/users_.php');
}
if ($controller_object->on_object_create()) {
//    $controller_object->board_create_object->create_object->set_create_custom_template(APP_VIEWS_PATH . '/create-templates/users_.php');
}
if ($controller_object->on_object_update()) {
    if (!session_db::check_user_level(['god', 'admin'])) {
        $controller_object->db_table->set_field_constants(["user_login" => session_db::get_user_data()['user_login']]);
        $controller_object->db_table->set_field_constants(["user_level" => session_db::get_user_data()['user_level']]);
        $controller_object->db_table->set_field_constants(["location_id" => session_db::get_user_data()['location_id']]);
        $controller_object->db_table->set_field_constants(["job_title_id" => session_db::get_user_data()['job_title_id']]);
        $controller_object->db_table->set_field_constants(["dep_id" => session_db::get_user_data()['dep_id']]);
        $controller_object->db_table->set_field_constants(["job_title_id" => session_db::get_user_data()['job_title_id']]);
    }
//    $controller_object->board_update_object->update_object->set_create_custom_template(APP_VIEWS_PATH . '/create-templates/users_.php');
}

$controller_object->start_board();

if (\k1lib\urlrewrite\url::get_url_level_value_by_name('row-keys-text') == session_db::get_user_login()) {
    DOM::menu_left()->set_active('nav-my-profile');
} else {
    DOM::menu_left()->set_active('nav-agency-users');
}
// LIST
if ($controller_object->on_board_list()) {
    if ($controller_object->on_object_list()) {
        $controller_object->board_list_object->list_object->apply_link_on_field_filter($controller_object->get_controller_root_dir() . "{$controller_object->get_board_read_url_name()}/--rowkeys--/?auth-code=--authcode--", ['user_names']);
    }
}

$controller_object->exec_board();

$controller_object->finish_board();


if ($controller_object->on_board_read()) {
    $related_div = $div->append_div("row k1lib-crudlexs-related-data");

    $sub_title = new \k1lib\html\h3("Task Orders");
    $sub_title->set_attrib("style", "margin-top:1em;");
    $sub_title->append_to($related_div);

    /**
     * ENUM('unread', 'read', 'working', 'finished', 'delivered', 'modifications', 'closed') 
     */
    $user_task_orders = new \k1lib\crudlexs\class_db_table($db, "task_orders");
    $custom_sql = "SELECT 
            view_user_task_orders.to_id,
            view_user_task_orders.to_name Task,
            view_user_task_orders.to_priority as Priority,
            view_user_task_orders.to_delivery_date 'Deliver',
            view_user_task_orders.to_delivery_time Hour,
            view_user_task_orders.client_id,
            view_user_task_orders.contract_id,
            view_user_task_orders.project_id
        FROM view_user_task_orders";
    $user_task_orders->set_custom_sql_query($custom_sql, FALSE);
    $user_task_orders->set_order_by('Deliver', 'DESC');


    if ($user_task_orders->get_state()) {
        $user_task_orders->set_query_filter(['to_state' => 'unread'], TRUE, FALSE);
        $controller_object->board_read_object->set_related_show_all_data(FALSE);
        $controller_object->board_read_object->set_related_show_new(FALSE);
        $users_subdetail_div = $controller_object->board_read_object->create_related_list($user_task_orders, ["Task"], "New", client_task_orders_config::ROOT_URL, client_task_orders_config::BOARD_CREATE_URL, client_task_orders_config::BOARD_READ_URL, client_task_orders_config::BOARD_LIST_URL);
        $users_subdetail_div->append_to($related_div);
    }
    if ($user_task_orders->get_state()) {
        $user_task_orders->clear_query_filter();
        $user_task_orders->set_query_filter(['to_state' => 'read'], TRUE, FALSE);
        $controller_object->board_read_object->set_related_show_all_data(FALSE);
        $controller_object->board_read_object->set_related_show_new(FALSE);
        $users_subdetail_div = $controller_object->board_read_object->create_related_list($user_task_orders, ["Task"], "Read", client_task_orders_config::ROOT_URL, client_task_orders_config::BOARD_CREATE_URL, client_task_orders_config::BOARD_READ_URL, client_task_orders_config::BOARD_LIST_URL);
        $users_subdetail_div->append_to($related_div);
    }
    if ($user_task_orders->get_state()) {
        $user_task_orders->clear_query_filter();
        $user_task_orders->set_query_filter(['to_state' => 'working'], TRUE, FALSE);
        $controller_object->board_read_object->set_related_show_all_data(FALSE);
        $controller_object->board_read_object->set_related_show_new(FALSE);
        $users_subdetail_div = $controller_object->board_read_object->create_related_list($user_task_orders, ["Task"], "Working", client_task_orders_config::ROOT_URL, client_task_orders_config::BOARD_CREATE_URL, client_task_orders_config::BOARD_READ_URL, client_task_orders_config::BOARD_LIST_URL);
        $users_subdetail_div->append_to($related_div);
    }
    if ($user_task_orders->get_state()) {
        $user_task_orders->clear_query_filter();
        $user_task_orders->set_query_filter(['to_state' => 'finished'], TRUE, FALSE);
        $controller_object->board_read_object->set_related_show_all_data(FALSE);
        $controller_object->board_read_object->set_related_show_new(FALSE);
        $users_subdetail_div = $controller_object->board_read_object->create_related_list($user_task_orders, ["Task"], "Finished", client_task_orders_config::ROOT_URL, client_task_orders_config::BOARD_CREATE_URL, client_task_orders_config::BOARD_READ_URL, client_task_orders_config::BOARD_LIST_URL);
        $users_subdetail_div->append_to($related_div);
    }
    if ($user_task_orders->get_state()) {
        $user_task_orders->clear_query_filter();
        $user_task_orders->set_query_filter(['to_state' => 'delivered'], TRUE, FALSE);
        $controller_object->board_read_object->set_related_show_all_data(FALSE);
        $controller_object->board_read_object->set_related_show_new(FALSE);
        $users_subdetail_div = $controller_object->board_read_object->create_related_list($user_task_orders, ["Task"], "On delivered", client_task_orders_config::ROOT_URL, client_task_orders_config::BOARD_CREATE_URL, client_task_orders_config::BOARD_READ_URL, client_task_orders_config::BOARD_LIST_URL);
        $users_subdetail_div->append_to($related_div);
    }
    if ($user_task_orders->get_state()) {
        $user_task_orders->clear_query_filter();
        $user_task_orders->set_query_filter(['to_state' => 'modifications'], TRUE, FALSE);
        $controller_object->board_read_object->set_related_show_all_data(FALSE);
        $controller_object->board_read_object->set_related_show_new(FALSE);
        $users_subdetail_div = $controller_object->board_read_object->create_related_list($user_task_orders, ["Task"], "On modifications", client_task_orders_config::ROOT_URL, client_task_orders_config::BOARD_CREATE_URL, client_task_orders_config::BOARD_READ_URL, client_task_orders_config::BOARD_LIST_URL);
        $users_subdetail_div->append_to($related_div);
    }
    if ($user_task_orders->get_state()) {
        $user_task_orders->clear_query_filter();
        $user_task_orders->set_query_filter(['to_state' => 'closed'], TRUE, FALSE);
        $controller_object->board_read_object->set_related_show_all_data(FALSE);
        $controller_object->board_read_object->set_related_show_new(FALSE);
        $users_subdetail_div = $controller_object->board_read_object->create_related_list($user_task_orders, ["Task"], "Closed", client_task_orders_config::ROOT_URL, client_task_orders_config::BOARD_CREATE_URL, client_task_orders_config::BOARD_READ_URL, client_task_orders_config::BOARD_LIST_URL);
        $users_subdetail_div->append_to($related_div);
    }
}

$body->content()->append_child($div);
