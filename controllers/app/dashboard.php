<?php

namespace k1app;

// This might be different on your proyect

use k1lib\html\template as template;
use \k1lib\urlrewrite\url as url;
use k1app\k1app_template as DOM;

\k1lib\session\session_db::is_logged(TRUE, APP_URL . 'app/log/form/');

\k1lib\sql\sql_query($db, "SET sql_mode='';");

k1app_template::start_template();

$content = DOM::html()->body()->content();

template::load_template('header');
template::load_template('app-header');
template::load_template('app-footer');

DOM::set_title(3, "K1 App Dashboard");

DOM::menu_left()->set_active('nav-dashboard');

$filter_url_value = \k1lib\forms\check_single_incomming_var(url::set_url_rewrite_var(url::get_url_level_count(), 'filter_url_value', FALSE));
$filter_data_url_value = \k1lib\forms\check_single_incomming_var(url::set_url_rewrite_var(url::get_url_level_count(), 'digitador_url_value', FALSE));

$content->append_h1("Vista rapida");
$content->set_class("dashboard");

/**
 * HTML GRID DEFINITION
 */
$content_grid = new \k1lib\html\foundation\grid(2, 2, $content);

//$row1_col1 = $content_grid->row(1)->set_class('expanded')->cell(1)->large(6)->medium(12)->small(12);
$row1_col1 = $content_grid->row(1)->set_class('grid-margin-x', TRUE)->cell(1)->large(6)->medium(12)->small(12);
$row1_col2 = $content_grid->row(1)->cell(2)->large(6)->medium(12)->small(12);

//$row2_col1 = $content_grid->row(2)->set_class('expanded')->cell(1)->large(6)->medium(12)->small(12);
$row2_col1 = $content_grid->row(2)->cell(1)->large(6)->medium(12)->small(12);
$row2_col2 = $content_grid->row(2)->cell(2)->large(6)->medium(12)->small(12);

/**
 * GRID ROW 1
 */
/**
 * GRID ROW 1 COL 1
 */
$row1_col1->append_h4("Row 1 Column 1");

$data_table[] = ['Data 1', 'Value 1'];
$data_table[] = ['Data 2', 'Value 2'];

$table1 = new \k1lib\html\foundation\table_from_data();
$table1->append_to($row1_col1);

$table1->set_data($data_table);

/**
 * GRID ROW 1 COL 2
 */
// this week
$row1_col2->append_h4("Row 1 Column 2");

$table2 = new \k1lib\html\foundation\table_from_data();
$table2->append_to($row1_col2);

$table2->set_data($data_table);

/**
 * GRID ROW 2
 */
/**
 * GRID ROW 2 COL 1
 */
//$row2_col1->append_h4("GRID ROW 2 COL 1");

/**
 * GRID ROW 2 COL 2
 */
//$row2_col2->append_h4("GRID ROW 2 COL 2");
