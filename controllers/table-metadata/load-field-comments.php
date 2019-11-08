<?php

namespace k1app;

use k1lib\html\template as template;
use k1app\k1app_template as DOM;

$body = DOM::html()->body();

template::load_template('header');
template::load_template('app-header');
template::load_template('app-footer');

$span = (new \k1lib\html\span("subheader"))->set_value("Load metada for: ");
DOM::set_title(3, $span . \k1lib\sql\get_db_database_name($db));

DOM::html()->head()->set_title(K1APP_TITLE . " | {$span->get_value()} " . \k1lib\sql\get_db_database_name($db));

DOM::menu_left_tail()->set_active('nav-fields-metadata');

$div_container = new \k1lib\html\div("row");

$form_create = (new \k1lib\html\form());
$form_create->append_to($div_container);

$div_row_buttons = $form_create->append_div("row");

$submit_button = $form_create->append_submit_button("Make SQL", TRUE);
$submit_button->append_to($div_row_buttons);

$form_create->append_div("row clearfix");
/**
 * @var \k1lib\html\textarea
 */
$textarea = new \k1lib\html\textarea("load-info");
$textarea->set_attrib("rows", 10)->append_to($form_create);


$table_config_to_use = [];
$post_data_to_change = [];
$sql_field_update_comments = "";
$sql_update_comment = "";
if (isset($_POST['load-info']) && !empty($_POST['load-info'])) {

    $load_info = \k1lib\forms\check_single_incomming_var($_POST['load-info']);
    $textarea->set_value($load_info);

    $load_info_by_lines = explode(PHP_EOL, $load_info);
    foreach ($load_info_by_lines as $line => $field_comment_line) {
        list($db_table_to_use, $field, $comment) = explode("\t", $field_comment_line);
        $comment = str_replace("\n", "", $comment);
        $comment = str_replace("\r", "", $comment);
        $db_table = new \k1lib\crudlexs\class_db_table($db, $db_table_to_use);
        $table_definitions = \k1lib\sql\get_table_definition_as_array($db, $db_table_to_use);

//        foreach ($db_table->get_db_table_config() as $field => $config) {
        if (isset($table_definitions[$field])) {
            $sql_update_comment = "ALTER TABLE `{$db_table_to_use}` CHANGE `{$field}` `{$field}` {$table_definitions[$field]} COMMENT '{$comment}';\n";
            $sql_field_update_comments .= $sql_update_comment;
        } else {
            trigger_error("FIELD definition of $field did not found to update", E_USER_WARNING);
        }
//        }
    }


    $submit_button->set_value("Exectute SQL", FALSE);

    $textarea_result = new \k1lib\html\textarea("result-sql");
    $textarea_result->set_attrib("rows", 10)->append_to($form_create);
    $textarea_result->set_value($sql_field_update_comments);

    if (isset($_POST['result-sql']) && !empty($_POST['result-sql'])) {
        $result_sql = \k1lib\forms\check_single_incomming_var($_POST['result-sql']);

        $textarea_result->set_value($result_sql);

        $result_sql_by_lines = explode(PHP_EOL, $result_sql);
        $result_sql_by_lines = str_replace("\'", "'", $result_sql_by_lines);
        $result_sql_by_lines = str_replace("\n", "", $result_sql_by_lines);
        $result_sql_by_lines = str_replace("\r", "", $result_sql_by_lines);

        $div_result = new \k1lib\html\div();
        $div_result->append_to($form_create);

        foreach ($result_sql_by_lines as $line => $field_comment_line) {
            if (!empty($field_comment_line)) {
                if (\k1lib\sql\sql_query($db, $field_comment_line) !== FALSE) {
                    $p = $div_result->append_p("OK - $field_comment_line");
                } else {
                    $p = $div_result->append_p("FAIL - $field_comment_line");
                }
            }
        }
    }
}

$body->content()->append_child($div_container);
