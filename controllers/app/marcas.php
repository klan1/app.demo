<?php

namespace k1app;

// This might be different on your proyect

use k1lib\html\template as template;
use \k1lib\urlrewrite\url as url;
use k1app\k1app_template as DOM;

$body = DOM::html()->body();

template::load_template('header');
template::load_template('app-header');
template::load_template('app-footer');

DOM::menu_left()->set_active('nav-brands');

$db_table_to_use = "brands";
$controller_name = "Marcas";

/**
 * ONE LINE config: less codign, more party time!
 */
$controller_object = new \k1lib\crudlexs\controller_base(APP_BASE_URL, $db, $db_table_to_use, $controller_name, 'k1lib-title-3');
$controller_object->set_config_from_class("\k1app\brands_config");

/**
 * ALL READY, let's do it :)
 */
$div = $controller_object->init_board();

// THIS IS ALWAYS NEEDED IF THE CREATE CALL COMES FROM ANOTHER TABLE
$controller_object->read_url_keys_text_for_create('clients');

$controller_object->start_board();

// LIST
if ($controller_object->on_object_list()) {
    $read_url = url::do_url($controller_object->get_controller_root_dir() . "{$controller_object->get_board_read_url_name()}/--rowkeys--/", ["auth-code" => "--authcode--"]);
    $controller_object->board_list_object->list_object->apply_link_on_field_filter($read_url, \k1lib\crudlexs\crudlexs_base::USE_LABEL_FIELDS);
}
//READ
if ($controller_object->on_object_read()) {
    /**
     * Custom Links
     */
    $agency_id = \k1lib\session\session_plain::get_user_data()['agency_id'];

    // Project LINK
    $controller_object->board_read_object->read_object->apply_link_on_field_filter(APP_BASE_URL . clients_config::ROOT_URL . '/' . clients_config::BOARD_READ_URL . "/--customfieldvalue--/?auth-code=--fieldauthcode--&back-url=" . urlencode($_SERVER['REQUEST_URI']), ['client_id'], ['client_id']);
}

$controller_object->exec_board();

$controller_object->finish_board();

if ($controller_object->on_board_read()) {
    $related_div = $div->append_div("row k1lib-crudlexs-related-data");
    /**
     * Related list
     */
    $related_db_table = new \k1lib\crudlexs\class_db_table($db, "brands_has_presentations");
    $controller_object->board_read_object->set_related_show_all_data(FALSE);
    $related_list = $controller_object->board_read_object->create_related_list($related_db_table, NULL, "Presentaciones de Marca", brands_has_presentation_config::ROOT_URL, brands_has_presentation_config::BOARD_CREATE_URL, brands_has_presentation_config::BOARD_READ_URL, brands_has_presentation_config::BOARD_LIST_URL, TRUE);
    $related_list->append_to($related_div);
}

$body->content()->append_child($div);
