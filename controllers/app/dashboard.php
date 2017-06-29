<?php

namespace k1app;

// This might be different on your proyect

use k1lib\html\template as template;
use \k1lib\urlrewrite\url as url;
use k1app\k1app_template as DOM;

\k1lib\session\session_db::is_logged(TRUE, APP_URL . 'log/form/');

//\k1lib\sql\sql_query($db, "SET sql_mode='';");


$content = DOM::html()->body()->content();

template::load_template('header');
template::load_template('app-header');
template::load_template('app-footer');

DOM::set_title(3, "Dashboard");

DOM::menu_left()->set_active('nav-dashboard');


$content->append_h1("Dashboard");
$content->set_class("dashboard");

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
$row1_col2->append_h4("Row 1 Column 1");


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
