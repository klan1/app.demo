<?php

namespace k1app;

use k1lib\html\template as template;
use \k1lib\urlrewrite\url as url;
use k1app\k1app_template as DOM;

$body = DOM::html()->body();

template::load_template('header');
template::load_template('app-header');
template::load_template('app-footer');

DOM::html()->head()->set_title(K1APP_TITLE . " | Auto APP");

DOM::menu_left_tail()->set_active('nav-table-explorer');

$span = (new \k1lib\html\span("subheader"))->set_value("Tables of DB ");
DOM::set_title(3, $span . \k1lib\sql\get_db_database_name($db));


$db_tables = \k1lib\sql\sql_query($db, "show tables", TRUE);

$ul = new \k1lib\html\ul();

foreach ($db_tables as $row_field => $row_value) {
    $table_to_link = $row_value["Tables_in_" . \k1lib\sql\get_db_database_name($db)];
    $table_alias = \k1lib\db\security\db_table_aliases::encode($table_to_link);

    if (strstr($table_to_link, "view_")) {
        continue;
    }

    $a_crudlexs = new \k1lib\html\a(url::do_url("../crudlexs/{$table_alias}/", [], FALSE), $table_to_link);
    $ul->append_li()->set_value($a_crudlexs);
}
$body->content()->append_h4("Choose a table");
$body->content()->append_child($ul);
