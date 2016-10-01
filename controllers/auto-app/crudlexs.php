<?php

namespace k1app;

require_once 'controllers-config.php';

use \k1lib\templates\temply as temply;
use \k1lib\urlrewrite\url as url;
use \k1lib\html\DOM as DOM;

$body = DOM::html()->body();

include temply::load_template("header", APP_TEMPLATE_PATH);
include temply::load_template("app-header", APP_TEMPLATE_PATH);
include temply::load_template("html-parts/app-footer", APP_TEMPLATE_PATH);

$table_alias = \k1lib\urlrewrite\url::set_url_rewrite_var(\k1lib\urlrewrite\url::get_url_level_count(), "row_key_text", FALSE);
$db_table_to_use = \k1lib\db\security\db_table_aliases::decode($table_alias);

$span = (new \k1lib\html\span("subheader"))->set_value("Auto app of table: ");
$top_bar->set_title(3, $span . $db_table_to_use);

DOM::html()->head()->set_title(APP_TITLE . " | {$span->get_value()} {$db_table_to_use}");

/**
 * ONE LINE config: less codign, more party time!
 */
$controller_object = new \k1lib\crudlexs\controller_base(APP_BASE_URL, $db, $db_table_to_use, $db_table_to_use);
$controller_object->set_config_from_class("\k1app\crudlexs_config");
$controller_object->set_security_no_rules_enable(TRUE);

/**
 * ALL READY, let's do it :)
 */
$div = $controller_object->init_board();

// CREATE
if (isset($controller_object->board_create_object)) {
    if (isset($_GET['no-rules']) && $_GET['no-rules'] == "1") {
        $controller_object->board_create_object->set_show_rule_to_apply(NULL);
        $controller_object->board_create_object->set_apply_label_filter(FALSE);
        $controller_object->board_create_object->set_apply_field_label_filter(FALSE);
    }
}
// READ
if ($controller_object->on_board_read()) {
    if (isset($_GET['no-rules']) && $_GET['no-rules'] == "1") {
        $controller_object->board_read_object->set_show_rule_to_apply(NULL);
        $controller_object->board_read_object->set_apply_label_filter(FALSE);
        $controller_object->board_read_object->set_apply_field_label_filter(FALSE);
        $controller_object->board_read_object->set_use_label_as_title_enabled(FALSE);
    }
}
// UPDATE
if (isset($controller_object->board_update_object)) {
    if (isset($_GET['no-rules']) && $_GET['no-rules'] == "1") {
        $controller_object->board_update_object->set_show_rule_to_apply(NULL);
        $controller_object->board_update_object->set_apply_label_filter(FALSE);
        $controller_object->board_update_object->set_apply_field_label_filter(FALSE);
    }
}
// DELETE
if (isset($controller_object->board_delete_object)) {
    if (isset($_GET['no-rules']) && $_GET['no-rules'] == "1") {
        $controller_object->board_delete_object->set_show_rule_to_apply(NULL);
    }
}
// LIST
if ($controller_object->on_board_list()) {
    if (isset($_GET['no-rules']) && $_GET['no-rules'] == "1") {
        $controller_object->board_list_object->set_show_rule_to_apply(NULL);
        $controller_object->board_list_object->set_apply_label_filter(FALSE);
        $controller_object->board_list_object->set_apply_field_label_filter(FALSE);
    }

    /**
     * BACK
     */
    $back_link = \k1lib\html\get_link_button(urldecode("../../../"), "Back");
    $back_link->append_to($controller_object->board_div_content);
}

$controller_object->start_board();

// LIST
if ($controller_object->on_board_list()) {
    if ($controller_object->on_object_list()) {
        $controller_object->board_list_object->list_object->apply_link_on_field_filter(
                url::do_url($controller_object->get_controller_root_dir() . "{$controller_object->get_board_read_url_name()}/--rowkeys--/", ["auth-code" => "--authcode--"])
                , (isset($_GET['no-rules']) ? \k1lib\crudlexs\crudlexs_base::USE_KEY_FIELDS : \k1lib\crudlexs\crudlexs_base::USE_LABEL_FIELDS)
        );
    }
}

$controller_object->exec_board(FALSE);

$controller_object->finish_board();

$body->content()->append_child($div);
