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

DOM::menu_left()->set_active('nav-ecards-user-sends');

$db_table_to_use = "ecard_sends";
$controller_name = "User Sends";

/**
 * ONE LINE config: less codign, more party time!
 */
$controller_object = new \k1lib\crudlexs\controller_base(APP_BASE_URL, $db, $db_table_to_use, $controller_name, 'k1lib-title-3');
$controller_object->set_config_from_class('\k1app\ecards_sends_config');
//
///**
// * USER LOGIN AS CONSTANT
// */
//$controller_object->db_table->set_field_constants(["user_login" => session_db::get_user_login()]);

/**
 * ALL READY, let's do it :)
 */
$div = $controller_object->init_board();

if ($controller_object->on_board_list()) {
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

    // eCard LINK
    $ecard_url = url::do_url(APP_BASE_URL . ecards_config::ROOT_URL . '/' . ecards_config::BOARD_READ_URL . '/--customfieldvalue--/', $get_params);
    $controller_object->object_read()->apply_link_on_field_filter($ecard_url, ['ecard_id'], ['ecard_id']);

    // User LINK
    $user_url = url::do_url(APP_BASE_URL . users_config::ROOT_URL . '/' . users_config::BOARD_READ_URL . '/--customfieldvalue--/', $get_params);
    $controller_object->object_read()->apply_link_on_field_filter($user_url, ['user_id'], ['user_id']);
}

$controller_object->exec_board();

$controller_object->finish_board();

if ($controller_object->on_board_read()) {
    // LOAD ECARD CLASS
    include 'ecard-generation.php';
//    include 'emailer.php';

    $ecard_send_data = $controller_object->db_table->get_data(FALSE)[1];

    $mode = ($ecard_send_data['ecard_mode'] == 'h') ? ECARD_HORIZONTAL : ECARD_VERTICAL;

    $ecard = new ecard_generator($ecard_send_data['ecard_id'], $mode, $ecard_send_data['send_id']);
    $ecard->set_image_proportion(0.8);

    if (\k1lib\forms\check_single_incomming_var($_GET['action']) == 'send-email') {
//        $ecard->send_email('alejo@klan1.com');
        $ecard->send_email(NULL, FALSE);
    }

    /**
     * HTML OUTPUT
     */
    $div_ecard = new \k1lib\html\div();
    $div_ecard->append_to($div);

    $div_ecard->append_a(url::do_url($_SERVER['REQUEST_URI'], ['action' => 'send-email']), " Send ecard", NULL, 'button success fi-mail');
    $div_ecard->append_child(new \k1lib\html\h3('Preview'));
    $div_ecard->append_child($ecard->get_ecard_img_tag());
}

if ($controller_object->on_board_read()) {
    $related_div = $div->append_div("row k1lib-crudlexs-related-data");
    /**
     * Related list
     */
    $related_db_table = new \k1lib\crudlexs\class_db_table($db, "ecard");
    $controller_object->board_read_object->set_related_show_all_data(FALSE);
    $related_list = $controller_object->board_read_object->create_related_list($related_db_table, NULL, "eCards", ecards_config::ROOT_URL, ecards_config::BOARD_CREATE_URL, ecards_config::BOARD_READ_URL, ecards_config::BOARD_LIST_URL, TRUE);
    $related_list->append_to($related_div);
}

$body->content()->append_child($div);
