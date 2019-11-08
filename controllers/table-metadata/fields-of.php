<?php

namespace k1app;

use k1lib\html\template as template;
use k1app\k1app_template as DOM;
use \k1lib\urlrewrite\url as url;

$body = DOM::html()->body();

template::load_template('header');
template::load_template('app-header');
template::load_template('app-footer');

DOM::menu_left_tail()->set_active('nav-manage-tables');

$table_alias = \k1lib\urlrewrite\url::set_url_rewrite_var(\k1lib\urlrewrite\url::get_url_level_count(), "row_key_text", FALSE);
$db_table_to_use = \k1lib\db\security\db_table_aliases::decode($table_alias);

$span = (new \k1lib\html\span("subheader"))->set_value("Field of: ");
DOM::set_title(3, $span . $db_table_to_use . ' [' . \k1lib\sql\get_db_database_name($db) . ']');

DOM::html()->head()->set_title(K1APP_TITLE . " | {$span->get_value()} {$db_table_to_use}");


$db_table = new \k1lib\crudlexs\class_db_table($db, $db_table_to_use);

/**
 * TOP BAR - Tables added to menu
 */
$db_tables = \k1lib\sql\sql_query($db, "show tables", TRUE);

$table_explorer_menu = DOM::menu_left_tail()->add_sub_menu("#", "DB Tables", 'nav-db-table-list', 'nav-manage-tables');

foreach ($db_tables as $row_field => $row_value) {
    $table_to_link = $row_value["Tables_in_" . \k1lib\sql\get_db_database_name($db)];
    $table_alias_link = \k1lib\db\security\db_table_aliases::encode($table_to_link);

    if (strstr($table_to_link, "view_")) {
        continue;
    }
    $table_explorer_menu->add_menu_item(url::do_url("../{$table_alias_link}/", [], FALSE), $table_to_link, 'nav-' . $table_alias_link);
}
DOM::menu_left_tail()->set_active('nav-' . $table_alias);
/**
 * END TOP BAR - Tables added to menu
 */
$div_result = new \k1lib\html\div();
$div_ok = $div_result->append_div();
$p_fail = $div_result->append_p();
$p_unchanged = $div_result->append_p();

if ($db_table->get_state()) {

    if (isset($_POST["submit-it"])) {
        unset($_POST["submit-it"]);
        $table_config = $db_table->get_db_table_config();
        $table_config_to_use = [];
        foreach ($_POST as $field => $config) {
            $options_values = [];
            $comment_values = [];
            $table_config_to_use[$field] = \k1lib\common\clean_array_with_guide($config, $table_config[$field]);
            foreach ($table_config_to_use[$field] as $option_name => $option_value) {
                if ($option_value === TRUE) {
                    $option_value = "yes";
                } elseif ($option_value === FALSE) {
                    $option_value = "no";
                }
                $options_values[] = "$option_name:$option_value";
            }
            $comment_values[$field] = implode(",", $options_values);
            if ($comment_values) {
                $table_definitions = \k1lib\sql\get_table_definition_as_array($db, $db_table_to_use);
                foreach ($comment_values as $field => $comment_to_update) {
                    if (isset($table_definitions[$field])) {
                        $sql_update_comment = "ALTER TABLE `{$db_table_to_use}` CHANGE `$field` `$field` {$table_definitions[$field]} COMMENT '{$comment_values[$field]}'";
                        if (!empty($comment_values[$field])) {
                            if (\k1lib\sql\sql_query($db, $sql_update_comment) !== FALSE) {
                                $div_ok->append_p("$sql_update_comment (ok) ", TRUE);
                            } else {
                                $p_fail->set_value("$field (fail)", TRUE);
                            }
                        } else {
                            $p_unchanged->set_value("$field (unchached)", TRUE);
                        }
                    } else {
                        trigger_error("FIELD definition of $field did not found to update", E_USER_WARNING);
                    }
                }
            }
        }
    }

    $div_container = new \k1lib\html\div("row");
    $form = (new \k1lib\html\form());
    $form->append_to($div_container);

    $div_row_buttons = new \k1lib\html\div("row");
    $div_row_buttons->append_child(\k1lib\html\get_link_button("../../show-tables/", "Back"));
    $div_row_buttons->append_child(\k1lib\html\get_link_button("./", "Cancel"));
    $div_row_buttons->append_child($form->append_submit_button("Save changes", TRUE));

    $form->append_child($div_row_buttons);
    $form->append_child(new \k1lib\html\div("row clearfix"));
//    $div_row_fieldset = new \k1lib\html\div("row");

    $ul = new \k1lib\html\ul("accordion");
    $ul->set_attrib("data-accordion", TRUE);
    $ul->set_attrib('data-allow-all-closed="true"', TRUE);

    $table_config_to_use = [];
    $post_data_to_change = [];
    foreach ($db_table->get_db_table_config(TRUE) as $field => $config) {


        $table_config_to_use[$field] = \k1lib\common\clean_array_with_guide($config, $k1lib_field_config_options_defaults);

        foreach ($table_config_to_use[$field] as $option_name => $option_value) {

//            \k1lib\common\bolean_to_string($bolean)
            $make_radio = FALSE;
            if ($option_value === TRUE) {
                $option_value = "yes";
                $make_radio = TRUE;
            } elseif ($option_value === FALSE) {
                $option_value = "no";
                $make_radio = TRUE;
            }
            if ($make_radio) {
                $input_yes = new \k1lib\html\input("radio", "{$field}[{$option_name}]", "yes");
                $label_yes = new \k1lib\html\label("yes", "{$field}[{$option_name}]");
                $input_yes->post_code($label_yes->generate());

                $input_no = new \k1lib\html\input("radio", "{$field}[{$option_name}]", "no");
                $label_no = new \k1lib\html\label("no", "{$field}[{$option_name}]");
                $input_no->post_code($label_no->generate());

                if ($option_value == "yes") {
                    $input_yes->set_attrib("checked", TRUE);
                }
                if ($option_value == "no") {
                    $input_no->set_attrib("checked", TRUE);
                }
                $table_config_to_use[$field][$option_name] = $input_yes->generate() . " " . $input_no->generate();
            } else {
                $input = new \k1lib\html\input("text", "{$field}[{$option_name}]", $option_value);
                $input_generated = $input->generate();
                $table_config_to_use[$field][$option_name] = $input_generated;
            }
        }
        if (isset($_POST[$field])) {
            $post_data_to_change[$field] = implode(",", $_POST[$field]);
        }

        $li = $ul->append_li(null, "accordion-item")->set_attrib("data-accordion-item", TRUE);
        $a_title = (new \k1lib\html\a("#", $field))->set_attrib("class", "accordion-title k1lib-field-of-title")->append_to($li);
        $div_content = (new \k1lib\html\div('div_content'))->set_attrib("class", "accordion-content")->set_attrib("data-tab-content", TRUE)->append_to($li);
        \k1lib\html\generate_row_2columns_layout($div_content, $table_config_to_use[$field]);
    }

    $form->append_child($ul);
    $form->append_child($div_result);

    $body->content()->append_child($div_container);
}

