<?php

namespace k1app;

use k1lib\html\template as template;
use k1lib\session\session_db as session_db;
use k1lib\urlrewrite\url as url;
use k1app\k1app_template as DOM;

$body = DOM::html()->body();

template::load_template('header');
template::load_template('app-header');
template::load_template('app-footer');

DOM::menu_left()->set_active('nav-agency-menu');
DOM::menu_left()->set_active('nav-agency-my');

/**
 * ONE LINE config: less codign, more party time!
 */
$controller_object = new \k1lib\crudlexs\controller_base(APP_BASE_URL, $db, "agencies", "My agency", $top_bar);
$controller_object->set_config_from_class("\k1app\agency_my_agency_config");
\k1lib\crudlexs\listing::$rows_per_page = 5;

$controller_object->db_table->set_query_filter(["agency_id" => session_db::get_user_data()['agency_id']], TRUE);

/**
 * URL REDIRECT ->
 * to the Agency attached to the loged user
 */
if (empty($controller_object->get_controller_board_url_value())) {
    $get_params = ["auth-code" => md5(\k1lib\K1MAGIC::get_value() . \k1lib\session\session_db::get_user_data()['agency_id'])];
    $go_url = url::do_url(
                    "./{$controller_object->get_board_read_url_name()}/"
                    . session_db::get_user_data()['agency_id'] . "/", $get_params);
    \k1lib\html\html_header_go($go_url);
}

$div = $controller_object->init_board();

if ($controller_object->on_board_read()) {
    $controller_object->board_read_object->set_back_enable(FALSE);
}

$controller_object->start_board();

$controller_object->exec_board();

$controller_object->finish_board();

if ($controller_object->on_board_read()) {
    $related_div = $div->append_div("row k1lib-crudlexs-related-data");
    /**
     * Clients list
     */
    $clients_db_table = new \k1lib\crudlexs\class_db_table($db, "clients");
    $clients_subdetail_div = $controller_object->board_read_object->create_related_list($clients_db_table, ["client_name"], "Clients", client_clients_config::ROOT_URL, client_clients_config::BOARD_CREATE_URL, client_clients_config::BOARD_READ_URL, client_clients_config::BOARD_LIST_URL);
    $clients_subdetail_div->append_to($related_div);

    /**
     * Departments list
     */
    $departments_db_table = new \k1lib\crudlexs\class_db_table($db, "departments");
    $departments_subdetail_div = $controller_object->board_read_object->create_related_list($departments_db_table, ["dep_name"], "Departments", agency_departments_config::ROOT_URL, agency_departments_config::BOARD_CREATE_URL, agency_departments_config::BOARD_READ_URL, agency_departments_config::BOARD_LIST_URL);
    $departments_subdetail_div->append_to($related_div);
    /**
     * Locations list
     */
    $locations_db_table = new \k1lib\crudlexs\class_db_table($db, "locations");
    $locations_subdetail_div = $controller_object->board_read_object->create_related_list($locations_db_table, ["location_name"], "Locations", agency_locations_config::ROOT_URL, agency_locations_config::BOARD_CREATE_URL, agency_locations_config::BOARD_READ_URL, agency_locations_config::BOARD_LIST_URL);
    $locations_subdetail_div->append_to($related_div);
    /**
     * Users list
     */
    $users_db_table = new \k1lib\crudlexs\class_db_table($db, "users");
    $controller_object->board_read_object->set_related_show_new(FALSE);
    $users_subdetail_div = $controller_object->board_read_object->create_related_list($users_db_table, ["user_names"], "Users", agency_users_config::ROOT_URL, agency_users_config::BOARD_CREATE_URL, agency_users_config::BOARD_READ_URL, agency_users_config::BOARD_LIST_URL);
    $users_subdetail_div->append_to($related_div);
}

$body->content()->append_child($div);
