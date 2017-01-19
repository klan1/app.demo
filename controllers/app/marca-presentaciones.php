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

$db_table_to_use = "brands_has_presentations";
$controller_name = "Presentaciones de Marca";

/**
 * ONE LINE config: less codign, more party time!
 */
$controller_object = new \k1lib\crudlexs\controller_base(APP_BASE_URL, $db, $db_table_to_use, $controller_name, 'k1lib-title-3');
$controller_object->set_config_from_class("\k1app\brands_has_presentation_config");

/**
 * USER LOGIN AS CONSTANT
 */
$controller_object->db_table->set_field_constants(["user_login" => session_db::get_user_login()]);

/**
 * ALL READY, let's do it :)
 */
$div = $controller_object->init_board();

$controller_object->read_url_keys_text_for_create("brands");
$controller_object->read_url_keys_text_for_list("brands", TRUE);

$controller_object->start_board();

// LIST
if ($controller_object->on_object_list()) {
    $read_url = url::do_url($controller_object->get_controller_root_dir() . "{$controller_object->get_board_read_url_name()}/--rowkeys--/", ["auth-code" => "--authcode--"]);
    $controller_object->board_list_object->list_object->apply_link_on_field_filter($read_url, \k1lib\crudlexs\crudlexs_base::USE_LABEL_FIELDS);
}

$controller_object->exec_board();

$controller_object->finish_board();

if ($controller_object->on_board_read()) {
    $related_div = $div->append_div("row k1lib-crudlexs-related-data");
    /**
     * Related list - LASTEST
     */
    $related_db_table = new \k1lib\crudlexs\class_db_table($db, "presentation_specs");
    $related_db_table->set_group_by(['spec_type']);
    $related_db_table->set_order_by('datetime_in', 'DESC');
    //$controller_object->board_read_object->set_related_show_all_data(FALSE);
    $related_list = $controller_object->board_read_object->create_related_list($related_db_table, NULL, "Especificaciones Actuales", presentation_specs_config::ROOT_URL, presentation_specs_config::BOARD_CREATE_URL, presentation_specs_config::BOARD_READ_URL, presentation_specs_config::BOARD_LIST_URL, TRUE);
    $related_list->append_to($related_div);
    /**
     * Related list - ALL
     * No reuso el objeto de tabla por que no tengo metodos (aun) para lipiar todo 
     * sobre lo que se hizo SET_ en la anterior.
     */
//    $related_db_table = new \k1lib\crudlexs\class_db_table($db, "presentation_specs");
//    $controller_object->board_read_object->set_related_show_all_data(FALSE);
//    $controller_object->board_read_object->set_related_show_new(FALSE);
//    $related_db_table->set_order_by('datetime_in', 'DESC');
//    $related_list = $controller_object->board_read_object->create_related_list($related_db_table, NULL, "Historial de especificaciones", presentation_specs_config::ROOT_URL, presentation_specs_config::BOARD_CREATE_URL, presentation_specs_config::BOARD_READ_URL, presentation_specs_config::BOARD_LIST_URL, TRUE);
//    $related_list->append_to($related_div);
}

$body->content()->append_child($div);