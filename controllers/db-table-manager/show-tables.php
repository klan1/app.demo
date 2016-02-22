<?php

namespace k1app;

use \k1lib\templates\temply as temply;
use \k1lib\urlrewrite\url_manager as url_manager;

include temply::load_template("header", APP_TEMPLATE_PATH);
$span = new \k1lib\html\span_tag("subheader");
$span->set_value("Tables of database ");
temply::set_place_value("controller-name", $span->generate_tag() . \k1lib\sql\get_db_database_name($db));


$db_tables = \k1lib\sql\sql_query($db, "show tables", TRUE);

foreach ($db_tables as $row_field => $row_value) {
    $table_to_link = $row_value["Tables_in_" . \k1lib\sql\get_db_database_name($db)];
    $table_alias = \k1lib\db\security\db_table_aliases::encode($table_to_link);

    if (strstr($table_to_link, "view_")) {
        continue;
    }
    $p = new \k1lib\html\p_tag();

    $back_url = urlencode($_SERVER['REQUEST_URI']);
    $get_params = ['back-url' => $back_url];

    $a_manage = new \k1lib\html\a_tag(url_manager::do_url("../fields-of/{$table_alias}/", $get_params), "Configure");
    $p->set_value($table_to_link . " : " . $a_manage->generate_tag());
    $p->generate_tag(TRUE);
}

include temply::load_template("footer", APP_TEMPLATE_PATH);
