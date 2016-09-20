<?php

namespace k1app;

use \k1lib\templates\temply as temply;
use \k1lib\urlrewrite\url as url;
use \k1lib\html\DOM as DOM;

$body = DOM::html()->body();

include temply::load_template("header", APP_TEMPLATE_PATH);
include temply::load_template("html-parts/app-header", APP_TEMPLATE_PATH);
include temply::load_template("html-parts/app-footer", APP_TEMPLATE_PATH);

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

    $get_params = ['back-url' => $_SERVER['REQUEST_URI']];

    $a_manage = new \k1lib\html\a_tag(url::do_url("../fields-of/{$table_alias}/", $get_params), "Configure");
    $p->set_value($table_to_link . " : " . $a_manage->generate_tag());
    $body->content()->append_child($p);
}