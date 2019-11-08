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

DOM::menu_left()->set_active('nav-section-name');

$db_table_to_use = "table_to_use";
$controller_name = "Controller name to show";

/**
 * ONE LINE config: less codign, more party time!
 */
$controller_object = new \k1lib\crudlexs\controller_base(APP_BASE_URL, $db, $db_table_to_use, $controller_name, 'k1lib-title-3');
$controller_object->set_config_from_class('\k1app\table_config_class');

$controller_object->db_table->set_field_constants(['user_login' => session_db::get_user_login()]);

/**
 * ALL READY, let's do it :)
 */
$div = $controller_object->init_board();

//$controller_object->read_url_keys_text_for_list('referrer_table_caller');
//$controller_object->read_url_keys_text_for_create('referrer_table_caller');

if ($controller_object->on_board_list()) {
    $controller_object->board_list_object->set_create_enable(FALSE);
}

$controller_object->start_board();

// LIST
if ($controller_object->on_object_list()) {
    $read_url = url::do_url($controller_object->get_controller_root_dir() . "{$controller_object->get_board_read_url_name()}/--rowkeys--/", ["auth-code" => "--authcode--"]);
    $controller_object->board_list_object->list_object->apply_link_on_field_filter($read_url, \k1lib\crudlexs\crudlexs_base::USE_LABEL_FIELDS);
}
//if ($controller_object->on_object_read()) {
//    /**
//     * Custom Links
//     */
//    $get_params = [
//        'auth-code' => '--fieldauthcode--',
//        'back-url' => $_SERVER['REQUEST_URI']
//    ];
//    // Layout Horizontal LINK
//    $horizontal_layout_url = url::do_url(APP_BASE_URL . table_config_class::ROOT_URL . '/' . table_config_class::BOARD_UPDATE_URL . '/--customfieldvalue--/', $get_params);
//    $controller_object->object_read()->apply_link_on_field_filter($horizontal_layout_url, ['ecard_layout_h_id'], ['ecard_layout_h_id']);
//}

$controller_object->exec_board();

$controller_object->finish_board();

//if ($controller_object->on_board_read()) {
//    $related_div = $div->append_div("row k1lib-crudlexs-related-data");
//    /**
//     * Related list
//     */
//    $related_db_table = new \k1lib\crudlexs\class_db_table($db, "related_table_name");
////    $controller_object->board_read_object->set_related_show_all_data(FALSE);
////    $controller_object->board_read_object->set_related_show_new(FALSE);
//    $related_list = $controller_object->board_read_object->create_related_list($related_db_table, NULL, "Related title to show", related_table_config_class::ROOT_URL, related_table_config_class::BOARD_CREATE_URL, related_table_config_class::BOARD_READ_URL, related_table_config_class::BOARD_LIST_URL, FALSE);
//    $related_list->append_to($related_div);
//}

$body->content()->append_child($div);
