<?php

namespace k1app;

// This might be different on your proyect

use \k1lib\templates\temply as temply;
use \k1lib\urlrewrite\url as url;
use k1app\k1app_template as DOM;

$content = DOM::html()->body()->content();

include temply::load_template("header", APP_TEMPLATE_PATH);
include temply::load_template("app-header", APP_TEMPLATE_PATH);
include temply::load_template("app-footer", APP_TEMPLATE_PATH);

/**
 * $_GET['do-floors'] capture and creation button
 */
$incoming = \k1lib\forms\check_all_incomming_vars($_GET);
if (isset($incoming['do-floors'])) {
    $do_foors = TRUE;
    $content->append_child(new \k1lib\html\foundation\callout("Creating floors"));
} else {
    $do_floors_button = new \k1lib\html\a(url::do_url($_SERVER['REQUEST_URI'], ['do-floors' => 1], FALSE), "Crear pisos", "_self", "button small warning");
    $do_floors_button->append_to($content);
    $do_foors = FALSE;
}

/**
 * Open tables
 */
$warehouses = new \k1lib\crudlexs\class_db_table($db, "warehouses");
$wh_columns = new \k1lib\crudlexs\class_db_table($db, "wh_columns");
$wh_columns_rows = new \k1lib\crudlexs\class_db_table($db, "wh_column_rows");
$wh_positions = new \k1lib\crudlexs\class_db_table($db, "wh_positions");

$warehouses_data = $warehouses->get_data(TRUE);
$floors_default = 5;

if ($warehouses_data) {
    unset($warehouses_data[0]);
    /**
     * WAREHOUSES LEVEL
     */
    foreach ($warehouses_data as $wh) {
        $content->append_h2("Bodega: " . $wh['warehouse_name']);
        $content->append_p("Columnas: " . $wh['warehouse_columns']);
        // GET COLUMNS DATA
        $wh_columns->set_query_filter(['warehouse_id' => $wh['warehouse_id']], TRUE);
        $wh_columns_data = $wh_columns->get_data();
        /**
         * COLUMNS LEVEL
         */
        unset($wh_columns_data[0]);
        foreach ($wh_columns_data as $wh_column) {
            $content->append_h3("Columna " . $wh_column['wh_column_id']);
            $content->append_p("Filas: " . $wh_column['wh_column_rows']);
            /**
             * CREATE ROWS
             */
            for ($row = 1; $row <= $wh_column['wh_column_rows']; $row++) {
                $row_data = array(
                    'warehouse_id' => $wh_column['warehouse_id'],
                    'wh_column_id' => $wh_column['wh_column_id'],
                    'wh_column_row_id' => $row,
                );
                if ($wh_column['wh_column_rows'] == 1) {
                    $row_data['wh_column_row_floors'] = 1;
                } else {
                    $row_data['wh_column_row_floors'] = $floors_default;
                }
                if (\k1lib\sql\sql_insert($db, $wh_columns_rows->get_db_table_name(), $row_data)) {
                    $content->append_p(print_r($row_data, TRUE));
                    $content->append_p("Inserted!");
                } else {
                    $content->append_p("Row {$row} already exist.");
                }
            }
            // GET ROW DATA
            $wh_columns_rows->set_query_filter(['warehouse_id' => $wh_column['warehouse_id'], 'wh_column_id' => $wh_column['wh_column_id']], TRUE);
            $wh_columns_rows_data = $wh_columns_rows->get_data();
            /**
             * ROW LEVEL
             */
            unset($wh_columns_rows_data[0]);
            foreach ($wh_columns_rows_data as $wh_row) {
                $content->append_h4("Fila " . $wh_row['wh_column_row_id']);
                $content->append_p("Pisos: " . $wh_row['wh_column_row_floors']);
                /**
                 * FLOOR LEVEL
                 */
                for ($floor = 1; $floor <= $wh_row['wh_column_row_floors']; $floor++) {
                    $position = array(
                        'warehouse_id' => $wh_row['warehouse_id'],
                        'wh_column_id' => $wh_row['wh_column_id'],
                        'wh_column_row_id' => $wh_row['wh_column_row_id'],
                        'wh_position_id' => $floor,
                    );
                    /**
                     * Let's do the floors
                     */
                    if ($do_foors) {
                        if (\k1lib\sql\sql_insert($db, $wh_positions->get_db_table_name(), $position)) {
                            $content->append_p(print_r($position, TRUE));
                            $content->append_p("Floor inserted!");
                        } else {
                            $content->append_p("Floor {$floor} already exist.");
                        }
                    } else {
                        $content->append_p(print_r($position, TRUE));
                    }
                }
            }
            $wh_columns_rows->clear_query_filter();
        }
        $wh_columns->clear_query_filter();
    }
}
