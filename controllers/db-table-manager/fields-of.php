<?php

namespace k1app;

use \k1lib\templates\temply as temply;

include temply::load_template("header", APP_TEMPLATE_PATH);

$table_alias = \k1lib\urlrewrite\url_manager::set_url_rewrite_var(\k1lib\urlrewrite\url_manager::get_url_level_count(), "row_key_text", FALSE);
$db_table_to_use = \k1lib\db\security\db_table_aliases::decode($table_alias);

$span = new \k1lib\html\span_tag("subheader");
$span->set_value("Fields of: ");
temply::set_place_value("html-title", " | {$span->get_value()} {$db_table_to_use}");
temply::set_place_value("controller-name", $span->generate_tag() . $db_table_to_use);

$db_table = new \k1lib\crudlexs\class_db_table($db, $db_table_to_use);

$div_result = new \k1lib\html\div_tag();
$div_ok = $div_result->append_div();
$p_fail = $div_result->append_p();
$p_unchanged = $div_result->append_p();

if ($db_table->get_state()) {

    if (isset($_POST["submit-it"])) {
        unset($_POST["submit-it"]);
//        d($_POST, true);
//        exit;

        $table_config = $db_table->get_db_table_config();
//        d($table_config, true);
        $table_config_to_use = [];
        foreach ($_POST as $field => $config) {
            $options_values = [];
            $data_to_show = [];
            $table_config_to_use[$field] = \k1lib\common\clean_array_with_guide($config, $table_config[$field]);
            foreach ($table_config_to_use[$field] as $option_name => $option_value) {
                if ($option_value == "yes") {
                    $option_value = TRUE;
                } elseif ($option_value == "no") {
                    $option_value = FALSE;
                }

                if (empty($option_value) && $option_value !== FALSE) {
                    continue;
                }

                if ($option_value === $table_config[$field][$option_name]) {
                    continue;
                }

                if (($option_name == "validation") && ($option_value == $mysql_default_validation[$db_table->get_db_table_config()[$field]['type']])) {
                    continue;
                }
                if ($option_value === TRUE) {
                    $option_value = "yes";
                } elseif ($option_value === FALSE) {
                    $option_value = "no";
                }
                $options_values[] = "$option_name:$option_value";
            }
            $data_to_show[$field] = implode(",", $options_values);
            if ($data_to_show) {
                $table_definitions = \k1lib\sql\get_table_definition_as_array($db, $db_table_to_use);
                foreach ($data_to_show as $field => $comment_to_update) {
                    if (isset($table_definitions[$field])) {
                        $sql_tu_update_comment = "ALTER TABLE `{$db_table_to_use}` CHANGE `$field` `$field` {$table_definitions[$field]} COMMENT '{$data_to_show[$field]}'";
                        if (!empty($data_to_show[$field])) {
                            if (\k1lib\sql\sql_query($db, $sql_tu_update_comment) !== FALSE) {
                                $div_ok->append_p("$sql_tu_update_comment (ok) ", TRUE);
                            } else {
                                $p_fail->set_value("$field (fail)", TRUE);
                            }
                        } else {
                            $p_unchanged->set_value("$field (unchached)", TRUE);
//                            $p = $div_result->append_p("UNCHANGED - $field");
                        }
                    } else {
                        trigger_error("FIELD definition of $field did not found to update", E_USER_WARNING);
                    }
                }
            }
        }
    }

    $div_container = new \k1lib\html\div_tag("row");
    $form = (new \k1lib\html\form_tag());
    $form->append_to($div_container);

    $div_row_buttons = new \k1lib\html\div_tag("row");
    $div_row_buttons->append_child(\k1lib\html\get_link_button("../../show-tables/", "Back"));
    $div_row_buttons->append_child(\k1lib\html\get_link_button("./", "Cancel"));
    $div_row_buttons->append_child($form->append_submit_button("Save changes", TRUE));

    $form->append_child($div_row_buttons);
    $form->append_child(new \k1lib\html\div_tag("row clearfix"));
//    $div_row_fieldset = new \k1lib\html\div_tag("row");

    $ul = new \k1lib\html\ul_tag("accordion");
    $ul->set_attrib("data-accordion", TRUE);
    $ul->set_attrib('data-allow-all-closed="true"', TRUE);



    $table_config_to_use = [];
    $post_data_to_change = [];
    foreach ($db_table->get_db_table_config() as $field => $config) {


        $table_config_to_use[$field] = \k1lib\common\clean_array_with_guide($config, $k1lib_field_config_options_defaults);

        foreach ($table_config_to_use[$field] as $option_name => $option_value) {

//            \k1lib\common\bolean_to_string($bolean)
            $make_checkbox = FALSE;
            if ($option_value === TRUE) {
                $option_value = "yes";
//                $make_checkbox = TRUE;
            } elseif ($option_value === FALSE) {
                $option_value = "no";
//                $make_checkbox = TRUE;
            }
            if ($make_checkbox) {
                $input = new \k1lib\html\input_tag("checkbox", "{$field}[{$option_name}]", "yes");
                if ($option_value == "yes") {
                    $input->set_attrib("checked", TRUE);
                }
            } else {
                $input = new \k1lib\html\input_tag("text", "{$field}[{$option_name}]", $option_value);
            }
            $table_config_to_use[$field][$option_name] = $input->generate_tag();
        }
        if (isset($_POST[$field])) {
            $post_data_to_change[$field] = implode(",", $_POST[$field]);
        }


        $li = $ul->append_li("accordion-item")->set_attrib("data-accordion-item", TRUE);
        $a_title = (new \k1lib\html\a_tag("#", $field))->set_attrib("class", "accordion-title")->append_to($li);
        $div_content = (new \k1lib\html\div_tag($class))->set_attrib("class", "accordion-content")->set_attrib("data-tab-content", TRUE)->append_to($li);
        $div_content->set_value(\k1lib\html\make_row_2columns_layout($table_config_to_use[$field]));
//        $div_fielset = new \k1lib\html\div_tag("large-4 medium-6 small-12 columns end");
//        $fieldset = new \k1lib\html\fieldset_tag($field);
//        $fieldset->append_to($div_fielset);
////        $fieldset->set_attrib("class", "");
//        $fieldset->set_value(\k1lib\html\make_row_2columns_layout($table_config_to_use[$field]));
//        $div_row_fieldset->set_value($div_fielset->generate_tag(), TRUE);
    }

    $form->append_child($ul);
    $form->append_child($div_result);
//    $form->append_child($div_row_buttons);

    $div_container->generate_tag(TRUE);
}

include temply::load_template("footer", APP_TEMPLATE_PATH);
