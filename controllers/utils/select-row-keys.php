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
use k1lib\urlrewrite\url as url;

include temply::load_template("header", APP_TEMPLATE_PATH);


$static_vars_from_get = \k1lib\forms\check_all_incomming_vars($_GET);
unset($static_vars_from_get[\k1lib\URL_REWRITE_VAR_NAME]);

/**
 * ONE LINE config: less codign, more party time!
 */
$table_to_use = url::set_url_rewrite_var(url::get_url_level_count(), "table_to_use", FALSE);
$table_to_use_real = \k1lib\db\security\db_table_aliases::decode($table_to_use);
$controller_object = new \k1lib\crudlexs\controller_base(APP_BASE_URL, $db, $table_to_use_real, "Select data helper");

/**
 * ALL READY, let's do it :)
 */
if ($controller_object->get_state()) {
    /**
     * POST data catch
     */
    if (isset($_POST) && !empty($_POST) && !isset($_POST['k1send'])) {
        $post_data = \k1lib\forms\check_all_incomming_vars($_POST, "post-data");
        $back_url = \k1lib\urlrewrite\get_back_url();
        \k1lib\common\serialize_var($back_url, "back-url");
    }
    /**
     * URL and ENABLE config from the simple config class on ../index.php
     */
    $controller_object->set_board_create_enabled(FALSE);
    $controller_object->set_board_read_enabled(FALSE);
    $controller_object->set_board_update_enabled(FALSE);
    $controller_object->set_board_delete_enabled(FALSE);
    $controller_object->set_board_list_url_name("list");

    $controller_object->set_board_list_name("Select on a row link to use it");

    $controller_object->init_board();

    if ($controller_object->on_board_list()) {
        $controller_object->board_list_object->set_back_enable(FALSE);
        $reference_table_to_use = url::set_url_rewrite_var(url::get_url_level_count(), "reference_table_to_use", FALSE);
        $reference_table_to_use_real = \k1lib\db\security\db_table_aliases::decode($reference_table_to_use);
        $reference_db_table = new \k1lib\crudlexs\class_db_table($db, $reference_table_to_use_real);
        $creating_obj = new \k1lib\crudlexs\creating($reference_db_table, FALSE);
        $static_vars_from_get_decoded = $creating_obj->decrypt_field_names($static_vars_from_get);
        $controller_object->db_table->set_query_filter($static_vars_from_get_decoded, TRUE, TRUE);
        $controller_object->board_list_object->set_search_enable(FALSE);
    }
    if (isset($_GET['back-url'])) {
        $back_link = \k1lib\html\get_link_button('javascript:history.back()', "Back");
        $back_link->append_to($controller_object->board_div_content);
    }
    $controller_object->start_board();

// LIST
    if ($controller_object->on_board_list()) {
        if ($controller_object->on_object_list()) {
            $controller_object->board_list_object->list_object->apply_link_on_field_filter(APP_URL . "utils/send-row-keys/{$table_to_use}/--rowkeys--/{$reference_table_to_use}/", \k1lib\crudlexs\crudlexs_base::USE_LABEL_FIELDS);
        }
    }

    $controller_object->exec_board(TRUE);
}

include temply::load_template("footer", APP_TEMPLATE_PATH);
