<?php

/**
 * CONTROLLER WITH DETAIL LIST
 * Ver: 1.0
 * Autor: J0hnD03
 * Date: 2016-02-03
 * 
 */

namespace k1app;

use k1lib\urlrewrite\url as url;

/**
 * ONE LINE config: less codign, more party time!
 */
$table_to_use = url::set_url_rewrite_var(url::get_url_level_count(), "table_to_use", FALSE);
$table_to_use_real = \k1lib\db\security\db_table_aliases::decode($table_to_use);

$row_keys_text = url::set_url_rewrite_var(url::get_url_level_count(), "row_key_text", FALSE);

$reference_table_to_use = url::set_url_rewrite_var(url::get_url_level_count(), "reference_table_to_use", FALSE);
$reference_table_to_use_real = \k1lib\db\security\db_table_aliases::decode($reference_table_to_use);

$to_use_db_table = new \k1lib\crudlexs\class_db_table($db, $table_to_use_real);

$row_keys_array = \k1lib\sql\table_url_text_to_keys($row_keys_text, $to_use_db_table->get_db_table_config());

$reference_db_table = new \k1lib\crudlexs\class_db_table($db, $reference_table_to_use_real);
$create_obj = new \k1lib\crudlexs\creating($reference_db_table, FALSE);
$create_obj->set_do_table_field_name_encrypt();
$to_merge_array = [];
foreach ($row_keys_array as $field => $value) {
    $to_merge_array[$create_obj->encrypt_field_name($field)] = $value;
}

$post_data_saved = \k1lib\common\unserialize_var("post-data");
//\k1lib\common\unset_serialize_var("post-data");

$back_url_saved = \k1lib\common\unserialize_var("back-url");
//\k1lib\common\unset_serialize_var("back-url");

$array_to_send = array_merge($post_data_saved, $to_merge_array);
\k1lib\common\serialize_var($array_to_send, "post-data-to-use");
\k1lib\common\serialize_var($reference_db_table->get_db_table_config(), "post-data-table-config");

\k1lib\html\html_header_go($back_url_saved);