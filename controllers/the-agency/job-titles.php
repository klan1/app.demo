<?php

/**
 * CONTROLLER WITH DETAIL LIST
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

DOM::menu_left()->set_active('nav-agency-job-titles');

/**
 * ONE LINE config: less codign, more party time!
 */
$controller_object = new \k1lib\crudlexs\controller_base(APP_BASE_URL, $db, "job_titles", "Agency job titles", 'k1lib-title-3');
$controller_object->set_config_from_class("\k1app\agency_job_titles_config");

$controller_object->db_table->set_query_filter(["agency_id" => session_db::get_user_data()['agency_id']], TRUE);
$controller_object->db_table->set_field_constants(["agency_id" => session_db::get_user_data()['agency_id']]);

/**
 * ALL READY, let's do it :)
 */
$div = $controller_object->init_board();

$controller_object->read_url_keys_text_for_list("departments", FALSE);
$controller_object->read_url_keys_text_for_create("departments");

$controller_object->start_board();

// LIST
if ($controller_object->on_board_list()) {
    if ($controller_object->on_object_list()) {
        $controller_object->board_list_object->list_object->apply_link_on_field_filter($controller_object->get_controller_root_dir() . "{$controller_object->get_board_read_url_name()}/--rowkeys--/?auth-code=--authcode--", ['job_title_name']);
    }
}

$controller_object->exec_board();

$controller_object->finish_board();

if ($controller_object->on_board_read()) {
    $related_div = $div->append_div("row k1lib-crudlexs-related-data");

    $users_db_table = new \k1lib\crudlexs\class_db_table($db, "users");
    $controller_object->board_read_object->set_related_show_new(FALSE);
    $users_subdetail_div = $controller_object->board_read_object->create_related_list($users_db_table, ["user_names"], "Users", agency_users_config::ROOT_URL, agency_users_config::BOARD_CREATE_URL, agency_users_config::BOARD_READ_URL, agency_users_config::BOARD_LIST_URL);
    $users_subdetail_div->append_to($related_div);
}

$body->content()->append_child($div);
