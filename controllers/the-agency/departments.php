<?php

/**
 * CONTROLLER WITH DETAIL LIST
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
$controller_object = new \k1lib\crudlexs\controller_base(APP_BASE_URL, $db, "departments", "Agency departments", $top_bar);
$controller_object->set_config_from_class("\k1app\agency_departments_config");

$controller_object->db_table->set_query_filter(["agency_id" => session_db::get_user_data()['agency_id']], TRUE);
$controller_object->db_table->set_field_constants(["agency_id" => session_db::get_user_data()['agency_id']]);

/**
 * ALL READY, let's do it :)
 */
$div = $controller_object->init_board();

$controller_object->start_board();

// LIST
if ($controller_object->on_board_list()) {
    if ($controller_object->on_object_list()) {
        $controller_object->board_list_object->list_object->apply_link_on_field_filter($controller_object->get_controller_root_dir() . "{$controller_object->get_board_read_url_name()}/--rowkeys--/?auth-code=--authcode--", ['dep_name']);
    }
}

$controller_object->exec_board();

$controller_object->finish_board();


if ($controller_object->on_board_read()) {
    $related_div = $div->append_div("row k1-crudlexs-related-data");

    $job_titles_db_table = new \k1lib\crudlexs\class_db_table($db, "job_titles");
    $job_titles_subdetail_div = $controller_object->board_read_object->create_related_list($job_titles_db_table, ["job_title_name"], "Job titles", agency_job_titles_config::ROOT_URL, agency_job_titles_config::BOARD_CREATE_URL, agency_job_titles_config::BOARD_READ_URL, agency_job_titles_config::BOARD_LIST_URL);
    $job_titles_subdetail_div->append_to($related_div);
}

$body->content()->append_child($div);
