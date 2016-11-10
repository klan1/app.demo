<?php

namespace k1app;

// This might be different on your proyect

use k1lib\html\template as template;
use \k1lib\urlrewrite\url as url;
use k1app\k1app_template as DOM;

\k1lib\session\session_db::is_logged(TRUE, APP_URL . 'log/form/');

$content = DOM::html()->body()->content();

template::load_template('header');
template::load_template('app-header');
template::load_template('app-footer');

DOM::menu_left()->set_active('nav-index');

$warehouse_url_value = url::set_url_rewrite_var(url::get_url_level_count(), 'warehouse', FALSE);

$content->append_h1("Vista rapida");
$content->set_class("tablero");

/**
 * HTML GRID DEFINITION
 */
$content_grid = new \k1lib\html\foundation\grid(2, 2, $content);

//$row1_col1 = $content_grid->row(1)->set_class('expanded')->col(1)->large(6)->medium(12)->small(12);
$row1_col1 = $content_grid->row(1)->col(1)->large(6)->medium(12)->small(12);
$row1_col2 = $content_grid->row(1)->col(2)->large(6)->medium(12)->small(12);

//$row2_col1 = $content_grid->row(2)->set_class('expanded')->col(1)->large(6)->medium(12)->small(12);
$row2_col1 = $content_grid->row(2)->set_class('expanded')->col(1)->large(6)->medium(12)->small(12);
$row2_col2 = $content_grid->row(2)->set_class('expanded')->col(2)->large(6)->medium(12)->small(12);

/**
 * GRID ROW 1
 */
/**
 * GRID ROW 1 COL 1
 */
$row1_col1->append_h4("Utilizacion por bodega");
if ($warehouse_url_value) {
    $warehouse_filter = "WHERE ID = {$warehouse_url_value}";
    $product_filter = "WHERE product_position.warehouse_id = {$warehouse_url_value} AND product_position.product_exit IS NULL";
    $row1_col1->append_p(new \k1lib\html\a("../", "Ver todas"));
    $product_title_append = " EN BODEGA $warehouse_url_value";
} else {
    $warehouse_filter = '';
    $product_filter = "WHERE product_position.product_exit IS NULL";
    $product_title_append = "";
}

$sql_query = "SELECT *
FROM view_dashboard_warehouses
{$warehouse_filter}";


$warehouses_data = \k1lib\sql\sql_query($db, $sql_query, TRUE, TRUE);

if ($warehouses_data) {
    $wh_table = new \k1lib\html\foundation\table_from_data();
    $wh_table->append_to($row1_col1);

    $wh_table->set_data($warehouses_data);
    if (!$warehouse_url_value) {
        $wh_table->insert_tag_on_field(new \k1lib\html\a('./{{field:ID}}/', "{{field:BODEGA}}"), ['BODEGA']);
    }
}
/**
 * GRID ROW 1 COL 2
 */
$row1_col2->append_h4("Productos presentes{$product_title_append}");

$products = new \k1lib\crudlexs\class_db_table($db, "products");


$sql_query = "SELECT COD, PRODUCTO, SUM(PESO) as PESO
FROM view_dashboard_products
{$warehouse_filter}
GROUP BY PRODUCTO";

$products_data = \k1lib\sql\sql_query($db, $sql_query, TRUE, TRUE);
if ($products_data) {
    $product_table = new \k1lib\html\foundation\table_from_data();
    $product_table->append_to($row1_col2);

    $product_table->set_data($products_data);

    $product_table->set_fields_for_key_array_text(['COD']);
    $product_url = url::do_url(APP_URL . products_config::ROOT_URL . '/' . products_config::BOARD_READ_URL . '/{{field:COD}}/', ['auth-code' => '--authcode--', 'back-url' => $_SERVER['REQUEST_URI']]);
    $product_table->insert_tag_on_field(new \k1lib\html\a($product_url, "{{field:PRODUCTO}}"), ['PRODUCTO']);
}
/**
 * GRID ROW 2
 */
$db_table = new \k1lib\crudlexs\class_db_table($db, "product_position");
/**
 * GRID ROW 2 COL 1
 */
$row2_col1->append_h4("PRODUCTOS POR UBICAR{$product_title_append}");
/**
 * Non placed inventory
 */
$filter = [
    'wh_column_id' => NULL,
    'wh_column_row_id' => NULL,
    'wh_column_row_position_id' => NULL,
];
if ($warehouse_url_value) {
    $filter['warehouse_id'] = $warehouse_url_value;
}

$db_table->set_query_filter($filter, TRUE);
$db_table->set_order_by('product_datetime_in', 'DESC');

$list = new \k1lib\crudlexs\listing($db_table, NULL);
if ($list->load_db_table_data('show-related')) {
    $list->apply_field_label_filter();
    $list->apply_label_filter();

    $read_url = url::do_url(APP_URL . warehouses_inventory_config::ROOT_URL . '/' . warehouses_inventory_config::BOARD_UPDATE_URL . "/--rowkeys--/", ["auth-code" => "--authcode--", "back-url" => $_SERVER['REQUEST_URI']]);
    $list->apply_link_on_field_filter($read_url, ['product_position_cod']);


    $list->do_html_object();
    $list->get_html_table()
            ->hide_fields(['wh_column_id', 'wh_column_row_id', 'wh_position_id', 'user_login', 'product_valid', 'product_datetime_in'])
            ->append_to($row2_col1);
} else {
    $row2_col1->append_div("callout primary")->set_value("Sin datos para mostrar");
}


$span_view_all_in = new \k1lib\html\span();
$span_view_all_in->append_a(url::do_url(APP_URL . warehouses_inventory_config::ROOT_URL . '/' . warehouses_inventory_config::BOARD_LIST_URL . '/'), "(ver todos)");
$row2_col1->append_h4("ULTIMAS 10 ENTRADAS{$product_title_append} {$span_view_all_in}");
/**
 * Present inventory
 */
$db_table = new \k1lib\crudlexs\class_db_table($db, "product_position");
//$db_table->set_custom_sql_query('SELECT '
//        . 'product_position_id,'
//        . 'product_position_cod,'
//        . 'warehouse_id,'
//        . 'wh_column_id,'
//        . 'wh_column_row_id,'
//        . 'wh_position_id,'
//        . 'product_name AS PRODUCTO,'
//        . 'product_weight,'
//        . 'product_quantity,'
//        . 'product_datetime_in'
//        . ' FROM view_inventory_in');

$filter_exclude = [
    'wh_column_id' => NULL,
    'wh_column_row_id' => NULL,
    'wh_column_row_position_id' => NULL,
];
if ($warehouse_url_value) {
    $filter = [
        'warehouse_id' => $warehouse_url_value,
    ];
} else {
    $filter = [];
}
$db_table->set_query_filter($filter, TRUE);
$db_table->set_query_filter_exclude($filter_exclude, TRUE);
$db_table->set_order_by('product_datetime_in', 'DESC');

$list = new \k1lib\crudlexs\listing($db_table, NULL);
$list->set_rows_per_page(10);

if ($list->load_db_table_data('show-related')) {
    $list->apply_field_label_filter(['product_id']);
    $list->apply_label_filter();

    $read_url = url::do_url(APP_URL . warehouses_inventory_config::ROOT_URL . '/' . warehouses_inventory_config::BOARD_READ_URL . "/--rowkeys--/", ["auth-code" => "--authcode--", "back-url" => $_SERVER['REQUEST_URI']]);
    $list->apply_link_on_field_filter($read_url, ['product_position_cod']);

    $list->do_html_object();
    $list->get_html_table()
            ->hide_fields(['product_position_id', 'user_login', 'product_valid'])
            ->append_to($row2_col1->append_div('scroll-x'));
} else {
//    \k1lib\notifications\on_DOM::queue_mesasage($db_table->generate_sql_query());
    $row2_col1->append_div("callout primary")->set_value("Sin datos para mostrar");
}
/**
 * GRID ROW 2 COL 2
 */
$span_view_all_out = new \k1lib\html\span();
$span_view_all_out->append_a(url::do_url(APP_URL . warehouses_inventory_config::ROOT_URL . '/' . warehouses_inventory_config::BOARD_LIST_URL . '/?modo=pasado'), "(ver todos)");

$row2_col2->append_h4("ULTIMAS 10 SALIDAS{$product_title_append} {$span_view_all_out}");
/**
 * Past inventory
 */
$db_table = new \k1lib\crudlexs\class_db_table($db, "product_position");
$db_table->set_custom_sql_query('SELECT '
        . 'product_position_id,'
        . 'product_position_cod,'
        . 'warehouse_id,'
        . 'product_name,'
        . 'product_weight_out,'
        . 'product_weight_left,'
        . 'product_quantity_out,'
        . 'product_quantity_left,'
        . 'product_datetime_out'
        . ' FROM view_inventory_out');
$fields_labels = [
    'product_name' => 'PRODUCTO',
    'product_weight_out' => 'SALE(K)',
    'product_weight_left' => 'QUEDA(K)',
    'product_quantity_out' => 'SALE',
    'product_quantity_left' => 'QUEDA',
    'product_datetime_out' => 'FECHA SALIDA',
];
if ($warehouse_url_value) {
    $filter = [
        'warehouse_id' => $warehouse_url_value,
    ];
} else {
    $filter = [];
}
$db_table->set_query_filter($filter, TRUE);
$db_table->set_order_by('product_datetime_out', 'DESC');

$list = new \k1lib\crudlexs\listing($db_table, NULL);
$list->set_rows_per_page(10);

if ($list->load_db_table_data('show-related')) {
    $list->set_custom_field_labels($fields_labels);
    $list->apply_label_filter();

    $read_url = url::do_url(APP_URL . warehouses_inventory_config::ROOT_URL . '/' . warehouses_inventory_config::BOARD_READ_URL . "/--rowkeys--/", ["auth-code" => "--authcode--", "back-url" => $_SERVER['REQUEST_URI']]);
    $list->apply_link_on_field_filter($read_url, ['product_position_cod']);


    $list->do_html_object();
    $table = $list->get_html_table()
            ->hide_fields(['product_position_id', 'user_login', 'product_valid', 'product_datetime_in'])
            ->append_to($row2_col2->append_div('scroll-x'));
//    \k1lib\notifications\on_DOM::queue_mesasage($db_table->generate_sql_query());
} else {
//    \k1lib\notifications\on_DOM::queue_mesasage($db_table->generate_sql_query());
    $row2_col2->append_div("callout primary")->set_value("Sin datos para mostrar");
}