<?php

namespace k1app;

// This might be different on your proyect

use \k1lib\templates\temply as temply;
use \k1lib\urlrewrite\url as url;
use \k1lib\html\DOM as DOM;

\k1lib\session\session_db::is_logged(TRUE, APP_URL . 'log/form/');

$content = DOM::html()->body()->content();

include temply::load_template("header", APP_TEMPLATE_PATH);
include temply::load_template("app-header", APP_TEMPLATE_PATH);
include temply::load_template("app-footer", APP_TEMPLATE_PATH);

$warehouse_url_value = url::set_url_rewrite_var(url::get_url_level_count(), 'warehouse', FALSE);

//$content->append_h1(APP_TITLE);

$content_grid = new \k1lib\html\foundation\grid(1, 2, $content);

$col1 = $content_grid->row(1)->col(1)->large(6)->medium(6)->small(12);
$col2 = $content_grid->row(1)->col(2)->large(6)->medium(6)->small(12);

/**
 * COL 1
 */
$col1->append_h4("Utilizacion por bodega");
$warehouses = new \k1lib\crudlexs\class_db_table($db, "warehouses");
if ($warehouse_url_value) {
    $warehouse_filter = "WHERE product_position.warehouse_id = {$warehouse_url_value} AND product_position.product_exit IS NULL";
    $col1->append_p(new \k1lib\html\a("../", "Ver todas"));
} else {
    $warehouse_filter = 'WHERE product_position.product_exit IS NULL';
}

$sql_query = "SELECT warehouses.warehouse_id AS ID, 
	warehouses.warehouse_name AS BODEGA, 
	SUM(product_position.product_weight) as PESO
FROM warehouses INNER JOIN product_position ON warehouses.warehouse_id = product_position.warehouse_id
{$warehouse_filter}
GROUP BY warehouses.warehouse_id
ORDER BY warehouses.warehouse_id ASC";

$warehouses->set_custom_sql_query($sql_query);

$warehouses_data = $warehouses->get_data(TRUE);

if ($warehouses_data) {
    $wh_table = new \k1lib\html\foundation\table_from_data();
    $wh_table->append_to($col1);

    $wh_table->set_data($warehouses_data)->set_class('scroll');
    if (!$warehouse_url_value) {
        $wh_table->insert_tag_on_field(new \k1lib\html\a('./{{field:ID}}/', "{{field:BODEGA}}"), ['BODEGA']);
    }
}
/**
 * COL 2
 */
$col2->append_h4("Productos presentes");

$products = new \k1lib\crudlexs\class_db_table($db, "products");

$sql_query = "SELECT products.product_id AS COD, 
	products.product_name AS PRODUCTO, 
	SUM(product_position.product_weight) AS PESO
FROM products INNER JOIN product_position ON products.product_id = product_position.product_id
{$warehouse_filter}
GROUP BY products.product_id
ORDER BY PESO DESC";

$products->set_custom_sql_query($sql_query);

$products_data = $products->get_data(TRUE);

if ($products_data) {
    $product_table = new \k1lib\html\foundation\table_from_data();
    $product_table->append_to($col2);

    $product_table->set_data($products_data)->set_class('scroll');
    $product_table->set_fields_for_key_array_text(['COD']);
    $product_url = url::do_url(APP_URL . products_config::ROOT_URL . '/' . products_config::BOARD_READ_URL . '/{{field:COD}}/', ['auth-code' => '--authcode--', 'back-url' => $_SERVER['REQUEST_URI']]);
    $product_table->insert_tag_on_field(new \k1lib\html\a($product_url, "{{field:PRODUCTO}}"), ['PRODUCTO']);

//    $product_table->
}