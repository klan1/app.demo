<?php

namespace k1app;

use \k1lib\templates\temply as temply;
use \k1lib\urlrewrite\url as url;

include temply::load_template("header", APP_TEMPLATE_PATH);
$span = new \k1lib\html\span_tag("subheader");
$span->set_value("Tables of database ");
temply::set_place_value("controller-name", $span->generate_tag() . \k1lib\sql\get_db_database_name($db));


$db_tables = \k1lib\sql\sql_query($db, "show tables", TRUE);

$ul = new \k1lib\html\ul_tag();

foreach ($db_tables as $row_field => $row_value) {
    $table_to_link = $row_value["Tables_in_" . \k1lib\sql\get_db_database_name($db)];
    $table_alias = \k1lib\db\security\db_table_aliases::encode($table_to_link);

    if (strstr($table_to_link, "view_")) {
        continue;
    }
    $p = new \k1lib\html\p_tag();

    $a_crudlexs = new \k1lib\html\a_tag(url::do_url("../crudlexs/{$table_alias}/", [], FALSE), "with rules");
    $a_crudlexs_no_rules = new \k1lib\html\a_tag(url::do_url("../crudlexs/{$table_alias}/", ['no-rules' => 1]), "raw table");
    $ul->append_li()->set_value($table_to_link . " : " . $a_crudlexs->generate_tag() . " - " . $a_crudlexs_no_rules->generate_tag());
}

$ul->generate_tag(TRUE);

include temply::load_template("footer", APP_TEMPLATE_PATH);
