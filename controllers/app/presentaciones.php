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

DOM::menu_left()->set_active('nav-presentations');

$db_table_to_use = "presentations";
$controller_name = "Presentaciones";

/**
 * ONE LINE config: less codign, more party time!
 */
$controller_object = new \k1lib\crudlexs\controller_base(APP_BASE_URL, $db, $db_table_to_use, $controller_name, 'k1lib-title-3');
$controller_object->set_config_from_class("\k1app\presentations_config");

/**
 * USER LOGIN AS CONSTANT
 */
$controller_object->db_table->set_field_constants(["user_login" => session_db::get_user_login()]);

/**
 * ALL READY, let's do it :)
 */
$div = $controller_object->init_board();

// THIS IS ALWAYS NEEDED IF THE CREATE CALL COMES FROM ANOTHER TABLE
$controller_object->read_url_keys_text_for_create('products');

if ($controller_object->on_object_list()) {
    $controller_object->board_list_object->set_create_enable(FALSE);
}
$controller_object->start_board();

// LIST
if ($controller_object->on_object_list()) {
    $read_url = url::do_url($controller_object->get_controller_root_dir() . "{$controller_object->get_board_read_url_name()}/--rowkeys--/", ["auth-code" => "--authcode--"]);
    $controller_object->board_list_object->list_object->apply_link_on_field_filter($read_url, \k1lib\crudlexs\crudlexs_base::USE_LABEL_FIELDS);
}

if ($controller_object->on_object_read()) {
    /**
     * Custom Links
     */
    $get_params = [
        'auth-code' => '--fieldauthcode--',
        'back-url' => $_SERVER['REQUEST_URI']
    ];
    
    // Product LINK
    $product_url = url::do_url(APP_BASE_URL . products_config::ROOT_URL . '/' . products_config::BOARD_READ_URL . '/--customfieldvalue--/', $get_params);
    $controller_object->object_read()->apply_link_on_field_filter($product_url, ['product_id'], ['product_id']);
}
//Set Presentation Name with fields
if ($controller_object->on_object_update()) {
    if ($controller_object->object_update()->get_post_data_catched()) {
        $post_data = $controller_object->object_update()->get_post_data();

        $product_table = new \k1lib\crudlexs\class_db_table($db, 'products');
        $product_table->set_query_filter(['product_id'=>$post_data['product_id']], TRUE);
        $product_table_data = $product_table->get_data(FALSE);

        $presentation_name = $product_table_data['name'] . ' - ' . $post_data['weight'] . 'Kg' . ' - ' . $post_data['packaging_type'];

        $post_data_new['name'] = $presentation_name;
        $controller_object->object_update()->set_post_data($post_data_new);
    }
}

if ($controller_object->on_object_create()) {
    if ($controller_object->object_create()->get_post_data_catched()) {
        $post_data = $controller_object->object_create()->get_post_data();

        $product_table = new \k1lib\crudlexs\class_db_table($db, 'products');
        $product_table->set_query_filter(['product_id'=>$post_data['product_id']], TRUE);
        $product_table_data = $product_table->get_data(FALSE);

        $presentation_name = $product_table_data['name'] . ' - ' . $post_data['weight'] . 'Kg' . ' - ' . $post_data['packaging_type'];

        $post_data_new['name'] = $presentation_name;
        $controller_object->object_create()->set_post_data($post_data_new);
    }
}

$controller_object->exec_board();

$controller_object->finish_board();

if ($controller_object->on_board_read()) {
    $related_div = $div->append_div("row k1lib-crudlexs-related-data");
    /**
     * Related list
     */
    $related_db_table = new \k1lib\crudlexs\class_db_table($db, "brands_has_presentations");
    $controller_object->board_read_object->set_related_show_new(FALSE);
    $controller_object->board_read_object->set_related_show_all_data(FALSE);
    $related_list = $controller_object->board_read_object->create_related_list($related_db_table, NULL, "Marcas asiganadas", brands_has_presentation_config::ROOT_URL, brands_has_presentation_config::BOARD_CREATE_URL, brands_has_presentation_config::BOARD_READ_URL, brands_has_presentation_config::BOARD_LIST_URL, TRUE);
    $related_list->append_to($related_div);

    $second_related_div = $div->append_div("row k1lib-crudlexs-related-data");
}

$body->content()->append_child($div);
