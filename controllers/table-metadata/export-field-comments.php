<?php

namespace k1app;

header("Content-Type: text/plain");

use k1lib\html\template as template;

$db_tables = \k1lib\sql\sql_query($db, "show tables", TRUE);

foreach ($db_tables as $row_field => $row_value) {
    $db_table_to_use = $row_value["Tables_in_" . \k1lib\sql\get_db_database_name($db)];

    if (strstr($db_table_to_use, "view_")) {
        continue;
    }

    $db_table = new \k1lib\crudlexs\class_db_table($db, $db_table_to_use);

    if ($db_table->get_state()) {

        $table_config_to_use = [];
        foreach ($db_table->get_db_table_config() as $field => $config) {
            $options_values = [];
            $data_to_show = [];

            $table_config_to_use[$field] = \k1lib\common\clean_array_with_guide($config, $k1lib_field_config_options_defaults);
            foreach ($table_config_to_use[$field] as $option_name => $option_value) {
                if ($option_value === TRUE) {
                    $option_value = "yes";
                } elseif (($option_value === FALSE) || (empty($option_value) && is_bool($k1lib_field_config_options_defaults[$option_name]))) {
                    $option_value = "no";
                }
                $options_values[] = "$option_name:$option_value";
            }
            $data_to_show[$field] = implode(",", $options_values);
            if ($data_to_show) {
                $table_definitions = \k1lib\sql\get_table_definition_as_array($db, $db_table_to_use);
                foreach ($data_to_show as $field => $comment_to_update) {
                    if (isset($table_definitions[$field]) && !empty($data_to_show[$field])) {
                        $sql_tu_update_comment = "{$db_table_to_use}\t{$field}\t{$data_to_show[$field]}";
                        echo $sql_tu_update_comment . PHP_EOL;
                    } else {
                        if (!isset($table_definitions[$field])) {
                            trigger_error("FIELD definition of $field did not found to update", E_USER_WARNING);
                        }
                    }
                }
            }
        }
    }
}
exit;
