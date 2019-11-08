<?php

namespace k1app;

const APP_MODE = "shell";

require "../../bootstrap.php";

//k1_on_app_session(true, "/");
$app_init_time = microtime(true);

$db_table = "CEDULAS_VALLE";
$flag_table = "CEDULAS_FLAG";
$sql = "INSERT INTO {$flag_table} SET 
    tid = '$app_init_time',
    `c` = (SELECT
        {$db_table}.NUIP
FROM
        {$db_table}
LEFT OUTER JOIN {$flag_table} B ON {$db_table}.NUIP = B.c
WHERE
        B.c IS NULL AND
        ( state = 0 OR state = 3 )
        AND (CODMUNICIPIO = 7 OR CODMUNICIPIO = 55 OR CODMUNICIPIO = 82)
LIMIT 1)
";

$fetch_init_time = microtime(true);

do {
    while ($rh = $db->query($sql)) {

        $sql_get_cedula = "SELECT c FROM {$flag_table} WHERE tid = {$app_init_time}";
        $cedula_consultar = \k1lib\sql\sql_query($db, $sql_get_cedula, false);
        $cedula_row = $cedula_consultar['c'];
//        $resultado['faltantes'] = number_format($resultado['faltantes']);
//        echo "Consultando: {$cedula_row}, {$resultado['faltantes']} en espera.\n";
        echo "Consultando: {$cedula_row}\n";

        $censo_data = getCensoRegistraduria($cedula_row);

        $fetch_run_time = \round((microtime(true) - $fetch_init_time), 5);

        if ($censo_data !== false) {
            var_dump($censo_data);
            update_table_state($db_table, 2, $cedula_row);
        } else {
            echo "Cedula no inscrita\n";
            update_table_state($db_table, 4, $cedula_row);
        }

        \k1lib\sql\sql_del_row($db, $flag_table, "tid", $app_init_time);

        echo "Runtime: $fetch_run_time \n\n--------\n\n";
        $fetch_init_time = microtime(true);
        $k1_sql_cache = array();
        $form_errors = array();
        $controller_errors = array();
    }
    $remaining_sql = "SELECT count(NUIP) as faltantes FROM $db_table WHERE state = 0";
    $resultado = \k1lib\sql\sql_query($db, $remaining_sql, FALSE);
    if ($resultado['faltantes'] == 0) {
        \d("Se han acabdo las cedulas");
        break;
    } else {
        \d("Aun no se acaban las cedulas, continuando...");
    }
} while ($rh === FALSE);

$app_run_time = round((microtime(true) - $app_init_time), 5);
d("Total Runtime: $app_run_time -> :D");

function update_table_state($db_table, $state, $nuip) {
    global $db;
    // UPDATE the found assigned
    $update_sql = "UPDATE {$db_table} SET state = {$state} WHERE  NUIP={$nuip}";
    $db->query($update_sql);
}
