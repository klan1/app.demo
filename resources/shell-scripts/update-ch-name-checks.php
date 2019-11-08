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
if ($source_to_use == 'PROC') {
    $query_sql_base_name_list = "SELECT
	ced_digitada_id,
	cedula,
	censo_check,
	nombre_check,
	nombres_digitados,
	k1app_sie2017_v1.procuraduria_cache.nuip AS proc_nuip,
	null AS own_nuip
FROM
	cedulas_listado_2019
	LEFT JOIN k1app_sie2017_v1.procuraduria_cache ON cedulas_listado_2019.cedula = k1app_sie2017_v1.procuraduria_cache.nuip
WHERE
	nombre_check IS NULL 
	AND nombres_digitados IS NOT null
ORDER BY
	cedula_datein DESC";
}

if ($source_to_use == 'OWN') {
    $query_sql_base_name_list = "SELECT
	ced_digitada_id,
	cedula,
	censo_check,
	nombre_check,
	nombres_digitados,
	k1app_sie2017_v1.procuraduria_cache.nuip AS proc_nuip,
	k1app_sie2017_v1.nuip_nombres.nuip AS own_nuip
FROM
	cedulas_listado_2019
	LEFT JOIN k1app_sie2017_v1.procuraduria_cache ON cedulas_listado_2019.cedula = k1app_sie2017_v1.procuraduria_cache.nuip
	LEFT JOIN k1app_sie2017_v1.nuip_nombres ON cedulas_listado_2019.cedula = k1app_sie2017_v1.nuip_nombres.nuip 
WHERE
	nombre_check IS NULL 
	AND nombres_digitados IS NOT NULL
        AND k1app_sie2017_v1.nuip_nombres.nuip is not null
ORDER BY
	cedula_datein DESC";
}
if ($source_to_use == 'ALL') {
    $query_sql_base_name_list = "SELECT
	ced_digitada_id,
	cedula,
	censo_check,
	nombre_check,
	nombres_digitados,
	k1app_sie2017_v1.procuraduria_cache.nuip AS proc_nuip,
	k1app_sie2017_v1.nuip_nombres.nuip AS own_nuip
FROM
	cedulas_listado_2019
	LEFT JOIN k1app_sie2017_v1.procuraduria_cache ON cedulas_listado_2019.cedula = k1app_sie2017_v1.procuraduria_cache.nuip
	LEFT JOIN k1app_sie2017_v1.nuip_nombres ON cedulas_listado_2019.cedula = k1app_sie2017_v1.nuip_nombres.nuip 
WHERE
	nombre_check IS NULL 
	AND nombres_digitados IS NOT NULL
ORDER BY
	cedula_datein DESC";
}



$base_name_list_data = \k1lib\sql\sql_query($db, $query_sql_base_name_list);

$i = 0;
$i_max = count($base_name_list_data);
foreach ($base_name_list_data as $row => $name_data) {
    $i++;
    $check_censo = FALSE;
    echo "Row: $i of $i_max \n";
    print_r($name_data);
    if (empty($name_data['nombres_digitados']) || $name_data['nombres_digitados'] == '#REF!') {
        echo "Skiping - empty name\n";
        echo "-------------\n";
        continue;
    }
    if (empty($name_data['own_nuip'])) {
        if (($source_to_use == 'PROC') OR ( $source_to_use == 'ALL')) {
            echo "Will query procuraduria\n";
            $datos_procuraduria = get_procuraduria_name($db_sie, $name_data['cedula']);
//        sleep(2);
            if ($datos_procuraduria === FALSE) {
                echo "Skiping - No procuraduria\n";
                echo "-------------\n";
                continue;
            } else {
                if ($datos_procuraduria['nombre_1'] == '') {
                    echo "Will use censo cehck\n";
                    $check_censo = TRUE;
                }
                print_r($datos_procuraduria);
            }
        } else {
            echo "Skiping - source procuraduria\n";
            echo "-------------\n";
            continue;
        }
    } else {
        if (($source_to_use == 'OWN') OR ( $source_to_use == 'ALL')) {

            echo "Will use own nuip\n";
            echo "-------------\n";
        } else {
            echo "Skiping - source own nuip\n";
            echo "-------------\n";
            continue;
        }
    }

    $compare_sql_1 = "SELECT
        '{$name_data['nombres_digitados']}' as nombre_digitado,
	CONCAT_WS(
		' ',
		B.nombres,
		B.apellidos
	) AS nombres,
	MATCH (
		B.nombres,
		B.apellidos
	) AGAINST ( '{$name_data['nombres_digitados']}' IN BOOLEAN MODE ) AS score 
FROM
	k1app_sie_ch.cedulas_listado_2019 A
	JOIN k1app_sie2017_v1.nuip_nombres B ON A.cedula = B.nuip 
WHERE
	A.ced_digitada_id = {$name_data['ced_digitada_id']}";
    $compare_sql_2 = "SELECT
        '{$name_data['nombres_digitados']}' as nombre_digitado,
	CONCAT_WS(
		' ',
		B.nombre_1,
		B.nombre_2,
		B.apellido_1,
		B.apellido_2 
	) AS nombre_procuraduria,
	MATCH (
		B.nombre_1,
		B.nombre_2,
		B.apellido_1,
		B.apellido_2 
	) AGAINST ( '{$name_data['nombres_digitados']}' IN BOOLEAN MODE ) AS score 
FROM
	k1app_sie_ch.cedulas_listado_2019 A
	JOIN k1app_sie2017_v1.procuraduria_cache B ON A.cedula = B.nuip 
WHERE
	A.ced_digitada_id = {$name_data['ced_digitada_id']}";

    if (!empty($name_data['own_nuip'])) {
        $compare_result = \k1lib\sql\sql_query($db, $compare_sql_1, FALSE);
        echo "Comparing using own data!\n";
    } else {
        if (!$check_censo) {
            $compare_result = \k1lib\sql\sql_query($db, $compare_sql_2, FALSE);
            echo "Comparing using Procuraduria!\n";
        } else {
            echo "Checking with censo!\n";
            if ($name_data['censo_check'] == '1') {
                echo "Censo has data, so, checked!\n";
                $compare_result['score'] = 1;
            } else {
                $compare_result['score'] = 0;
                $update_data = array('ced_digitada_id' => $name_data['ced_digitada_id'], 'nombre_check' => 2);
                echo "Censo and procuraduria has NO data, so, skiping!\n";
                echo "-------------\n";
                continue;
            }
        }
    }

    if ($compare_result) {
        echo "Compare result: \n";
        print_r($compare_result) . "\n";

        if ($compare_result['score'] + 0 > 0) {
            $update_data = array('ced_digitada_id' => $name_data['ced_digitada_id'], 'nombre_check' => 1);
            echo "Name checked!\n";
        } else {
            $update_data = array('ced_digitada_id' => $name_data['ced_digitada_id'], 'nombre_check' => 0);
            echo "Name mismatch!\n";
        }
        if (\k1lib\sql\sql_update($db, 'cedulas_listado_2019', $update_data)) {
            echo "Row udpated.\n";
        }
    } else {
        echo "No procuraduria data\n\n";
    }
    echo "-------------\n";
}

