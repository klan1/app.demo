<?php

namespace k1app;

use \k1lib\templates\temply as temply;
use \k1lib\urlrewrite\url as url;

include temply::load_template("header", APP_TEMPLATE_PATH);

$table_alias = \k1lib\urlrewrite\url::set_url_rewrite_var(\k1lib\urlrewrite\url::get_url_level_count(), "row_key_text", FALSE);
$db_table_to_use = \k1lib\db\security\db_table_aliases::decode($table_alias);

$span = new \k1lib\html\span_tag("subheader");
$span->set_value("Auto app of table: ");
temply::set_place_value("html-title", " | {$span->get_value()} {$db_table_to_use}");
temply::set_place_value("controller-name", $span->generate_tag());



/**
 * ONE LINE config: less codign, more party time!
 */
$controller_object = new \k1lib\crudlexs\controller_base(APP_BASE_URL, $db, $db_table_to_use, $db_table_to_use);

/**
 * ALL READY, let's do it :)
 */
$div = $controller_object->init_board();

// CREATE
if (isset($controller_object->board_create_object)) {
    if (isset($_GET['no-rules']) && $_GET['no-rules'] == "1") {
        $controller_object->board_create_object->set_show_rule_to_apply(NULL);
    } else {
        $controller_object->board_create_object->create_object->set_use_create_custom_template(TRUE);
    }
}
// READ
if (isset($controller_object->board_read_object)) {
    if (isset($_GET['no-rules']) && $_GET['no-rules'] == "1") {
        $controller_object->board_read_object->set_show_rule_to_apply(NULL);
    } else {
        $controller_object->board_read_object->read_object->set_use_read_custom_template(TRUE);
    }
}
// UPDATE
if (isset($controller_object->board_update_object)) {
    if (isset($_GET['no-rules']) && $_GET['no-rules'] == "1") {
        $controller_object->board_update_object->set_show_rule_to_apply(NULL);
    } else {
        $controller_object->board_update_object->update_object->set_use_create_custom_template(TRUE);
    }
}
// DELETE
if (isset($controller_object->board_delete_object)) {
    if (isset($_GET['no-rules']) && $_GET['no-rules'] == "1") {
        $controller_object->board_delete_object->set_show_rule_to_apply(NULL);
    }
}
// LIST
if (isset($controller_object->board_list_object)) {
    if (isset($_GET['no-rules']) && $_GET['no-rules'] == "1") {
        $controller_object->board_list_object->set_show_rule_to_apply(NULL);
    }

    /**
     * BACK
     */
    $back_link = \k1lib\html\get_link_button(urldecode("../../../"), "Back");
    $back_link->append_to($controller_object->board_div_content);
}


$controller_object->start_board();

// LIST
if (isset($controller_object->board_list_object)) {
    if (isset($controller_object->board_list_object->list_object)) {
        $controller_object->board_list_object->list_object->apply_link_on_field_filter(
                url::do_url("../{$controller_object->get_board_read_url_name()}/--rowkeys--/", ["auth-code" => "--authcode--"])
                , (isset($_GET['no-rules']) ? \k1lib\crudlexs\crudlexs_base::USE_KEY_FIELDS : \k1lib\crudlexs\crudlexs_base::USE_LABEL_FIELDS)
        );
    }
}

$controller_object->exec_board(FALSE);

$controller_object->finish_board();

$div->generate_tag(TRUE);

include temply::load_template("footer", APP_TEMPLATE_PATH);
