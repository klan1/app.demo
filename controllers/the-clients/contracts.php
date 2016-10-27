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
$controller_object = new \k1lib\crudlexs\controller_base(APP_BASE_URL, $db, "contracts", "Agency contracts", $top_bar);
$controller_object->set_config_from_class("\k1app\client_contracts_config");
$controller_object->db_table->set_order_by("contract_datetime_in", "DESC");

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

$controller_object->read_url_keys_text_for_create("clients");
$controller_object->read_url_keys_text_for_list("clients", FALSE);

$controller_object->start_board();

// LIST
if ($controller_object->on_board_list()) {
    if ($controller_object->on_object_list()) {
        $controller_object->board_list_object->list_object->apply_link_on_field_filter($controller_object->get_controller_root_dir() . "{$controller_object->get_board_read_url_name()}/--rowkeys--/?auth-code=--authcode--", ['contract_name']);
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
}
$controller_object->exec_board();

$controller_object->finish_board();


if ($controller_object->on_board_read()) {
    $related_div = $div->append_div("row k1lib-crudlexs-related-data");
    $projects_db_table = new \k1lib\crudlexs\class_db_table($db, "projects");
    $projects_subdetail_div = $controller_object->board_read_object->create_related_list($projects_db_table, ["project_name"], "Projects", client_projects_config::ROOT_URL, client_projects_config::BOARD_CREATE_URL, client_projects_config::BOARD_READ_URL, client_projects_config::BOARD_LIST_URL);
    $projects_subdetail_div->append_to($related_div);
}
$body->content()->append_child($div);
