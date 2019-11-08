<?php

namespace k1app;

// Composer lines
const K1LIB_LANG = "en";
const APP_MODE = "shell";
const IN_K1APP = TRUE;

require_once '../../vendor/autoload.php';
require_once '../../settings/path-settings.php';
require_once '../../settings/config.php';

include 'procuraduria.php';

/*
 * USE HERE THE DB CONFIG SCRIPT FILE
 */
require_once 'db-ch-2019.php';
require_once 'db-sie-2018.php';

/**
 * AUTOLOAD FOR APP CLASES
 */
spl_autoload_register(function($className) {
    $className = str_replace("\\", DIRECTORY_SEPARATOR, $className);
    $file_to_load = APP_CLASSES_PATH . $className . '.php';
    include_once $file_to_load;
});

/**
 * CLI Params
 */
if (isset($argv)) {
    if (isset($argv[1])) {
        $source_to_use = $argv[1];
    } else {
        $source_to_use = 'ALL';
    }
}


/**
 * PHP
 */
/**
 * GET THE LIST OF NAMES TO CHECH
 */
$query_sql_base_name_list = "SELECT
	ced_digitada_id,
	cedula
FROM
	cedulas_listado_2019
	LEFT JOIN k1app_sie2017_v1.procuraduria_cache ON cedulas_listado_2019.cedula = k1app_sie2017_v1.procuraduria_cache.nuip 
WHERE
	nombre_check = 1 
	AND k1app_sie2017_v1.procuraduria_cache.nombre_1 IS NULL 
ORDER BY
	cedula_datein ASC";




$base_name_list_data = \k1lib\sql\sql_query($db, $query_sql_base_name_list);

$i = 0;
$i_max = count($base_name_list_data);
foreach ($base_name_list_data as $row => $name_data) {
    $i++;
    echo "-------------\n";
    echo "Row: $i of $i_max \n";
    print_r($name_data);

    $datos_procuraduria = get_procuraduria_name($db_sie, $name_data['cedula']);
//        sleep(2);
    if ($datos_procuraduria === FALSE) {
        echo "No procuraduria\n";
    } else {
        if ($datos_procuraduria['nombre_1'] == '') {
            echo "Empty procuraduria\n";
            $check_censo = TRUE;
        } else {
            print_r($datos_procuraduria);
            echo "Saved.\n";
        }
    }
} 