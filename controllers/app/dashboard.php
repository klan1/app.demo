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

DOM::set_title(3, K1APP_DESCRIPTION);

DOM::menu_left()->set_active('nav-tablero');

$filter_url_value = \k1lib\forms\check_single_incomming_var(url::set_url_rewrite_var(url::get_url_level_count(), 'filter_url_value', FALSE));
$filter_data_url_value = \k1lib\forms\check_single_incomming_var(url::set_url_rewrite_var(url::get_url_level_count(), 'digitador_url_value', FALSE));

$content->append_h1("Vista rapida");
$content->set_class("tablero");

/**
 * HTML GRID DEFINITION
 */
$content_grid = new \k1lib\html\foundation\grid(3, 2, $content);

$content_grid->row(1)->set_class('grid-margin-x', TRUE);
$content_grid->row(2)->set_class('grid-margin-x', TRUE);
$content_grid->row(3)->set_class('grid-margin-x', TRUE);

//$row1_col1 = $content_grid->row(1)->set_class('expanded')->col(1)->large(6)->medium(12)->small(12);
$row1_col1 = $content_grid->row(1)->col(1)->large(6)->medium(12)->small(12);
$row1_col2 = $content_grid->row(1)->col(2)->large(6)->medium(12)->small(12);

//$row2_col1 = $content_grid->row(2)->set_class('expanded')->col(1)->large(6)->medium(12)->small(12);
$row2_col1 = $content_grid->row(2)->col(1)->large(6)->medium(12)->small(12);
$row2_col2 = $content_grid->row(2)->col(2)->large(6)->medium(12)->small(12);

$row3_col1 = $content_grid->row(3)->col(1)->large(6)->medium(12)->small(12);
$row3_col2 = $content_grid->row(3)->col(2)->large(6)->medium(12)->small(12);

/**
 * GRID ROW 1
 */
/**
 * GRID ROW 1 COL 1
 */
$row1_col1->append_h4("Progreso Total");

$data_listados_to_show = [];
$data_listados_to_show[0] = ['Indicador' => 'Indicador', 'Valor' => 'Valor', 'Porcentaje' => 'Porcentaje'];

// TOTAL DE LISTADOS
$sql_conteo_listados = "SELECT count(*) as listados FROM listados";
$conteo_listados_data = \k1lib\sql\sql_query($db, $sql_conteo_listados, FALSE, FALSE);
$data_listados_to_show[] = ['Indicador' => 'Listados digitados', 'Valor' => $conteo_listados_data['listados']];

// TOTAL DE CEDULAS
$sql_conteo_cedulas = "SELECT count(*) as cedulas FROM cedulas_listado";
$conteo_cedulas_data = \k1lib\sql\sql_query($db, $sql_conteo_cedulas, FALSE, FALSE);
$data_listados_to_show[] = ['Indicador' => 'Cédulas en listados', 'Valor' => $conteo_cedulas_data['cedulas'], '100'];

// TOTAL DE CEDULAS CONFIRMADAS
$sql_conteo_confirmadas_cedulas = "SELECT count(*) as cedulas FROM cedulas_listado WHERE cedulas_listado.censo_check = 1 AND cedulas_listado.nombre_check = 1;";
$conteo_cedulas_valido_data = \k1lib\sql\sql_query($db, $sql_conteo_confirmadas_cedulas, FALSE, FALSE);
$porcentaje_confirmadas = $conteo_cedulas_valido_data['cedulas'] * 100 / $conteo_cedulas_data['cedulas'];
$data_listados_to_show[] = ['Indicador' => 'Cédulas confirmadas', 'Valor' => $conteo_cedulas_valido_data['cedulas'], 'Porcentaje' => $porcentaje_confirmadas];

// TOTAL DE CEDULAS DESCARTADAS
$sql_conteo_descartadas_cedulas = "SELECT count(*) as cedulas FROM cedulas_listado WHERE cedulas_listado.censo_check = 0 OR cedulas_listado.nombre_check = 0;";
$conteo_cedulas_invalido_data = \k1lib\sql\sql_query($db, $sql_conteo_descartadas_cedulas, FALSE, FALSE);
$porcentaje_descartadas = $conteo_cedulas_invalido_data['cedulas'] * 100 / $conteo_cedulas_data['cedulas'];
$data_listados_to_show[] = ['Indicador' => 'Cédulas descartadas', 'Valor' => $conteo_cedulas_invalido_data['cedulas'], 'Porcentaje' => $porcentaje_descartadas];

// TOTAL DE CEDULAS DESCARTADAS
$sql_conteo_pendientes_cedulas = "SELECT count(*) as cedulas FROM cedulas_listado WHERE cedulas_listado.censo_check IS NULL OR cedulas_listado.nombre_check IS NULL;";
$conteo_cedulas_pendientes_data = \k1lib\sql\sql_query($db, $sql_conteo_pendientes_cedulas, FALSE, FALSE);
$porcentaje_pendientes = $conteo_cedulas_pendientes_data['cedulas'] * 100 / $conteo_cedulas_data['cedulas'];
$data_listados_to_show[] = ['Indicador' => 'Cédulas por confirmar', 'Valor' => $conteo_cedulas_pendientes_data['cedulas'], 'Porcentaje' => $porcentaje_pendientes];

// TOTAL DE CEDULAS DUPLICADAS
$sql_conteo_duplicadas_cedulas = "SELECT COUNT(*) AS cantidad FROM (SELECT cedula FROM view_cedulas_repetidas GROUP BY cedula) AS `CR`";
$conteo_cedulas_duplicadas_data = \k1lib\sql\sql_query($db, $sql_conteo_duplicadas_cedulas, FALSE, FALSE);
$porcentaje_duplicadas = $conteo_cedulas_duplicadas_data['cantidad'] * 100 / $conteo_cedulas_data['cedulas'];
$data_listados_to_show[] = ['Indicador' => 'Cédulas duplicadas', 'Valor' => $conteo_cedulas_duplicadas_data['cantidad'], 'Porcentaje' => $porcentaje_duplicadas];

// TOTAL DE LISTADOS
//$sql_conteo_validos = "SELECT count(*) as listados, listado_valido FROM listados GROUP BY listado_valido ORDER BY listado_valido DESC";
//$conteo_valido_data = \k1lib\sql\sql_query($db, $sql_conteo_validos, TRUE, FALSE);
//$data_listados_to_show[] = ['Indicador' => 'Validos', 'Valor' => $conteo_valido_data[1]['listados']];
//$data_listados_to_show[] = ['Indicador' => 'Invalidos', 'Valor' => $conteo_valido_data[2]['listados']];

$datos_listadoses_table = new \k1lib\html\foundation\table_from_data();
$datos_listadoses_table->append_to($row1_col1);

$datos_listadoses_table->set_data($data_listados_to_show);


/**
 * GRID ROW 1 COL 2
 */

/**
 * 
 * CALI
 * 
 */
$row1_col2->append_h4("Progreso CALI");

$data_cedulas_to_show_cali = [];
$data_cedulas_to_show_cali[0] = ['Indicador' => 'Indicador', 'Valor' => 'Valor', 'Porcentaje' => 'Porcentaje'];
// TOTAL DE CEDULAS EN CALI
$sql_conteo_cali_cedulas = "SELECT count( * ) AS cantidad 
    FROM
	cedulas_listado
	 
    WHERE
	DPTO = 31
	AND MCPIO = 1";
$conteo_cedulas_cali_data = \k1lib\sql\sql_query($db, $sql_conteo_cali_cedulas, FALSE, FALSE);
$porcentaje_cali = 100;

// TOTAL DE CEDULAS EN CALI CONFIRMADAS
$sql_conteo_cali_cedulas_confirmadas = "SELECT count( * ) AS cantidad 
    FROM
	cedulas_listado
	 
    WHERE
	DPTO = 31
	AND MCPIO = 1
	AND ( cedulas_listado.censo_check = 1 AND cedulas_listado.nombre_check = 1 )";
$conteo_cedulas_cali_data_confirmadas = \k1lib\sql\sql_query($db, $sql_conteo_cali_cedulas_confirmadas, FALSE, FALSE);
$porcentaje_cali_confirmadas = $conteo_cedulas_cali_data_confirmadas['cantidad'] * 100 / $conteo_cedulas_cali_data['cantidad'];

// TOTAL DE CEDULAS EN CALI DESCARTADAS
$sql_conteo_cali_cedulas_descartadas = "SELECT count( * ) AS cantidad 
    FROM
	cedulas_listado
	 
    WHERE
	DPTO = 31
	AND MCPIO = 1
	AND ( cedulas_listado.censo_check = 0 OR cedulas_listado.nombre_check = 0 )";
$conteo_cedulas_cali_data_descartadas = \k1lib\sql\sql_query($db, $sql_conteo_cali_cedulas_descartadas, FALSE, FALSE);
$porcentaje_cali_descartadas = $conteo_cedulas_cali_data_descartadas['cantidad'] * 100 / $conteo_cedulas_cali_data['cantidad'];

// TOTAL DE CEDULAS EN CALI PENDIENTES
$sql_conteo_cali_cedulas_pendientes = "SELECT count( * ) AS cantidad 
    FROM
	cedulas_listado
	 
    WHERE
	DPTO = 31
	AND MCPIO = 1
	AND ( cedulas_listado.censo_check IS NULL OR cedulas_listado.nombre_check IS NULL )";
$conteo_cedulas_cali_data_pendientes = \k1lib\sql\sql_query($db, $sql_conteo_cali_cedulas_pendientes, FALSE, FALSE);
$porcentaje_cali_pendientes = $conteo_cedulas_cali_data_pendientes['cantidad'] * 100 / $conteo_cedulas_cali_data['cantidad'];

// TOTAL DE CEDULAS EN CALI CONFIRMADAS
//$porcentaje_cali_confirmadas = ($conteo_cedulas_cali_data['cantidad'] - $conteo_cedulas_cali_data_descartadas['cantidad']) * 100 / $conteo_cedulas_cali_data['cantidad'];
$data_cedulas_to_show_cali[] = ['Indicador' => 'Cédulas censo en Cali', 'Valor' => $conteo_cedulas_cali_data['cantidad'], 'Porcentaje' => $porcentaje_cali];
$data_cedulas_to_show_cali[] = ['Indicador' => 'Cédulas confirmadas', 'Valor' => $conteo_cedulas_cali_data_confirmadas['cantidad'], 'Porcentaje' => $porcentaje_cali_confirmadas];
$data_cedulas_to_show_cali[] = ['Indicador' => 'Cédulas descartadas', 'Valor' => $conteo_cedulas_cali_data_descartadas['cantidad'], 'Porcentaje' => $porcentaje_cali_descartadas];
$data_cedulas_to_show_cali[] = ['Indicador' => 'Cédulas por confirmar', 'Valor' => $conteo_cedulas_cali_data_pendientes['cantidad'], 'Porcentaje' => $porcentaje_cali_pendientes];

$datos_cedulas_table_cali = new \k1lib\html\foundation\table_from_data();
$datos_cedulas_table_cali->append_to($row1_col2);

$datos_cedulas_table_cali->set_data($data_cedulas_to_show_cali);


/**
 * 
 * VALLE SIN CALI
 * 
 */

$row1_col2->append_h4("Progreso VALLE");
$data_cedulas_to_show_valle = [];
$data_cedulas_to_show_valle[0] = ['Indicador' => 'Indicador', 'Valor' => 'Valor', 'Porcentaje' => 'Porcentaje'];

// TOTAL DE CEDULAS VALLE SIN CALI
$sql_conteo_fuera_cali_cedulas = "SELECT count( * ) AS cantidad 
    FROM
	cedulas_listado
	 
    WHERE
	DPTO = 31
	AND MCPIO <> 1";
$conteo_cedulas_fuera_cali_data = \k1lib\sql\sql_query($db, $sql_conteo_fuera_cali_cedulas, FALSE, FALSE);
$porcentaje_fuera_cali = 100;

// TOTAL DE CEDULAS VALLE SIN CALI CONFIRMADAS
$sql_conteo_fuera_cali_cedulas_confirmadas = "SELECT count( * ) AS cantidad 
    FROM
	cedulas_listado
	 
    WHERE
	DPTO = 31
	AND MCPIO <> 1
	AND ( cedulas_listado.censo_check = 1 AND cedulas_listado.nombre_check = 1 )";
$conteo_cedulas_fuera_cali_data_confirmadas = \k1lib\sql\sql_query($db, $sql_conteo_fuera_cali_cedulas_confirmadas, FALSE, FALSE);
$porcentaje_fuera_cali_confirmadas = $conteo_cedulas_fuera_cali_data_confirmadas['cantidad'] * 100 / $conteo_cedulas_fuera_cali_data['cantidad'];

// TOTAL DE CEDULAS VALLE SIN CALI DESCARTADAS
$sql_conteo_fuera_cali_cedulas_descartadas = "SELECT count( * ) AS cantidad 
    FROM
	cedulas_listado
	 
    WHERE
	DPTO = 31
	AND MCPIO <> 1
	AND ( cedulas_listado.censo_check = 0 OR cedulas_listado.nombre_check = 0 )";
$conteo_cedulas_fuera_cali_data_descartadas = \k1lib\sql\sql_query($db, $sql_conteo_fuera_cali_cedulas_descartadas, FALSE, FALSE);
$porcentaje_fuera_cali_descartadas = $conteo_cedulas_fuera_cali_data_descartadas['cantidad'] * 100 / $conteo_cedulas_fuera_cali_data['cantidad'];

// TOTAL DE CEDULAS VALLE SIN CALI PENDIENTES
$sql_conteo_fuera_cali_cedulas_pendientes = "SELECT count( * ) AS cantidad 
    FROM
	cedulas_listado
	 
    WHERE
	DPTO = 31
	AND MCPIO <> 1
	AND ( cedulas_listado.censo_check IS NULL OR cedulas_listado.nombre_check IS NULL )";
$conteo_cedulas_fuera_cali_data_pendientes = \k1lib\sql\sql_query($db, $sql_conteo_fuera_cali_cedulas_pendientes, FALSE, FALSE);
$porcentaje_fuera_cali_pendientes = $conteo_cedulas_fuera_cali_data_pendientes['cantidad'] * 100 / $conteo_cedulas_fuera_cali_data['cantidad'];

// TOTAL DE CEDULAS FUERA CALI CONFIRMADAS
//$porcentaje_fuera_cali_confirmadas = ($conteo_cedulas_fuera_cali_data['cantidad'] - $conteo_cedulas_fuera_cali_data_descartadas['cantidad']) * 100 / $conteo_cedulas_fuera_cali_data['cantidad'];
$data_cedulas_to_show_valle[] = ['Indicador' => 'Cédulas censo en Valle', 'Valor' => $conteo_cedulas_fuera_cali_data['cantidad'], 'Porcentaje' => $porcentaje_fuera_cali];
$data_cedulas_to_show_valle[] = ['Indicador' => 'Cédulas confirmadas', 'Valor' => $conteo_cedulas_fuera_cali_data_confirmadas['cantidad'], 'Porcentaje' => $porcentaje_fuera_cali_confirmadas];
$data_cedulas_to_show_valle[] = ['Indicador' => 'Cédulas descartadas', 'Valor' => $conteo_cedulas_fuera_cali_data_descartadas['cantidad'], 'Porcentaje' => $porcentaje_fuera_cali_descartadas];
$data_cedulas_to_show_valle[] = ['Indicador' => 'Cédulas por confirmar', 'Valor' => $conteo_cedulas_fuera_cali_data_pendientes['cantidad'], 'Porcentaje' => $porcentaje_fuera_cali_pendientes];

$datos_cedulas_table_valle = new \k1lib\html\foundation\table_from_data();
$datos_cedulas_table_valle->append_to($row1_col2);

$datos_cedulas_table_valle->set_data($data_cedulas_to_show_valle);

/**
 * 
 * OTROS DEPARTAMENTOS
 * 
 */

$row1_col2->append_h4("Progreso otros departamentos");
$data_cedulas_to_show_fuera_valle = [];
$data_cedulas_to_show_fuera_valle[0] = ['Indicador' => 'Indicador', 'Valor' => 'Valor', 'Porcentaje' => 'Porcentaje'];

// TOTAL DE CEDULAS VALLE SIN CALI
$sql_conteo_fuera_valle_cedulas = "SELECT count( * ) AS cantidad 
    FROM
	cedulas_listado
	 
    WHERE
	DPTO <> 31";
$conteo_cedulas_fuera_valle_data = \k1lib\sql\sql_query($db, $sql_conteo_fuera_valle_cedulas, FALSE, FALSE);
$porcentaje_fuera_valle = 100;

// TOTAL DE CEDULAS VALLE SIN CALI CONFIRMADAS
$sql_conteo_fuera_valle_cedulas_confirmadas = "SELECT count( * ) AS cantidad 
    FROM
	cedulas_listado
	 
    WHERE
	DPTO <> 31
	AND ( cedulas_listado.censo_check = 1 AND cedulas_listado.nombre_check = 1 )";
$conteo_cedulas_fuera_valle_data_confirmadas = \k1lib\sql\sql_query($db, $sql_conteo_fuera_valle_cedulas_confirmadas, FALSE, FALSE);
$porcentaje_fuera_valle_confirmadas = $conteo_cedulas_fuera_valle_data_confirmadas['cantidad'] * 100 / $conteo_cedulas_fuera_valle_data['cantidad'];

// TOTAL DE CEDULAS VALLE SIN CALI DESCARTADAS
$sql_conteo_fuera_valle_cedulas_descartadas = "SELECT count( * ) AS cantidad 
    FROM
	cedulas_listado
	 
    WHERE
	DPTO <> 31
	AND ( cedulas_listado.censo_check = 0 OR cedulas_listado.nombre_check = 0 )";
$conteo_cedulas_fuera_valle_data_descartadas = \k1lib\sql\sql_query($db, $sql_conteo_fuera_valle_cedulas_descartadas, FALSE, FALSE);
$porcentaje_fuera_valle_descartadas = $conteo_cedulas_fuera_valle_data_descartadas['cantidad'] * 100 / $conteo_cedulas_fuera_valle_data['cantidad'];

// TOTAL DE CEDULAS VALLE SIN CALI PENDIENTES
$sql_conteo_fuera_valle_cedulas_pendientes = "SELECT count( * ) AS cantidad 
    FROM
	cedulas_listado
	 
    WHERE
	DPTO <> 31
	AND ( cedulas_listado.censo_check IS NULL OR cedulas_listado.nombre_check IS NULL )";
$conteo_cedulas_fuera_valle_data_pendientes = \k1lib\sql\sql_query($db, $sql_conteo_fuera_valle_cedulas_pendientes, FALSE, FALSE);
$porcentaje_fuera_valle_pendientes = $conteo_cedulas_fuera_valle_data_pendientes['cantidad'] * 100 / $conteo_cedulas_fuera_valle_data['cantidad'];

// TOTAL DE CEDULAS FUERA CALI CONFIRMADAS
//$porcentaje_fuera_valle_confirmadas = ($conteo_cedulas_fuera_valle_data['cantidad'] - $conteo_cedulas_fuera_valle_data_descartadas['cantidad']) * 100 / $conteo_cedulas_fuera_valle_data['cantidad'];
$data_cedulas_to_show_fuera_valle[] = ['Indicador' => 'Cédulas censo otros departamentos', 'Valor' => $conteo_cedulas_fuera_valle_data['cantidad'], 'Porcentaje' => $porcentaje_fuera_valle];
$data_cedulas_to_show_fuera_valle[] = ['Indicador' => 'Cédulas confirmadas', 'Valor' => $conteo_cedulas_fuera_valle_data_confirmadas['cantidad'], 'Porcentaje' => $porcentaje_fuera_valle_confirmadas];
$data_cedulas_to_show_fuera_valle[] = ['Indicador' => 'Cédulas descartadas', 'Valor' => $conteo_cedulas_fuera_valle_data_descartadas['cantidad'], 'Porcentaje' => $porcentaje_fuera_valle_descartadas];
$data_cedulas_to_show_fuera_valle[] = ['Indicador' => 'Cédulas por confirmar', 'Valor' => $conteo_cedulas_fuera_valle_data_pendientes['cantidad'], 'Porcentaje' => $porcentaje_fuera_valle_pendientes];

$datos_cedulas_table_fuera_valle = new \k1lib\html\foundation\table_from_data();
$datos_cedulas_table_fuera_valle->append_to($row1_col2);

$datos_cedulas_table_fuera_valle->set_data($data_cedulas_to_show_fuera_valle);

/**
 * GRID ROW 2
 */
/**
 * GRID ROW 2 COL 1
 */
$row2_col1->append_h4("Rendimiento de digitacion");
if ($filter_url_value == 'digitador' && $filter_data_url_value) {
    $digitador_filter = " WHERE k1app_users.user_login = '$filter_data_url_value' ";
    $row2_col1->append_p(new \k1lib\html\a("../../", "Ver todas"));
} else {
    $digitador_filter = '';
}

$sql_query = "SELECT
	`k1app_users`.`user_login` AS `Login`,
	`k1app_users`.`user_names` AS `Nombre`,
	count( 0 ) AS `Listados` 
FROM
	(
		`listados`
		LEFT JOIN `k1app_users` ON ( ( `listados`.`user_login` = `k1app_users`.`user_login` ) ) 
	) 
    {$digitador_filter}
    GROUP BY
	`listados`.`user_login`"
        . " ORDER BY Listados DESC";


$progreso_data = \k1lib\sql\sql_query($db, $sql_query, TRUE, TRUE);
if ($progreso_data) {
    $progreso_table = new \k1lib\html\foundation\table_from_data();
    $progreso_table->append_to($row2_col1);

    $progreso_table->set_data($progreso_data);
    if (!$filter_data_url_value) {
        $progreso_table->insert_tag_on_field(new \k1lib\html\a('./digitador/{{field:Login}}/', "{{field:Login}}"), ['Login']);
    }
}
/**
 * GRID ROW 2 COL 2
 */
//if ($filter_url_value != 'digitador') {
//
//
//    if ($filter_url_value == 'recolector' && $filter_data_url_value) {
//        $digitador_filter = " WHERE k1app_users.user_login = '$filter_data_url_value' ";
//        $row1_col1->append_p(new \k1lib\html\a("../", "Ver todos"));
//        $digitador_title_append = " Por el digitador $filter_data_url_value";
//    } else {
//        $digitador_filter = '';
//        $digitador_title_append = "";
//    }
//
//    $row2_col2->append_h4("Progreso Recolectores {$digitador_title_append}");
//
//    $sql_query = "SELECT * FROM view_lideres_progreso";
//
//
//    $progreso_digitadores_data = \k1lib\sql\sql_query($db, $sql_query, TRUE, TRUE);
//
//    if ($progreso_digitadores_data) {
//        $progreso_digitadores_table = new \k1lib\html\foundation\table_from_data();
//        $progreso_digitadores_table->append_to($row2_col2);
//
//        $progreso_digitadores_table->set_data($progreso_digitadores_data);
//        if (!$filter_data_url_value) {
//            $progreso_digitadores_table->insert_tag_on_field(new \k1lib\html\a('./recolector/{{field:recolector}}/', "{{field:recolector}}"), ['recolector']);
//        }
//    }
//}

/**
 * GRID ROW 3
 */
if ($filter_url_value == 'digitador' && $filter_data_url_value) {
    $digitador_filter = " WHERE (user_login = '$filter_data_url_value') ";
    $digitador_title_append = " de $filter_data_url_value";
} else {
    $digitador_filter = '';
    $digitador_title_append = "";
}

/**
 * GRID ROW 3 COL 1
 */
$row3_col1->append_h4("Listados por dia" . $digitador_title_append);

$sql_query = "select cast(`listados`.`listado_date_in` as date) AS `ingreso`,count(0) AS `cantidad` from `listados` {$digitador_filter} group by `ingreso`";

$progreso_listados_data = \k1lib\sql\sql_query($db, $sql_query, TRUE, TRUE);
if ($progreso_data) {
    $progreso_table = new \k1lib\html\foundation\table_from_data();
    $progreso_table->append_to($row3_col1);

    $progreso_table->set_data($progreso_listados_data);
}
/**
 * GRID ROW 2 COL 2
 */
$row3_col2->append_h4("Cedulas por dia" . $digitador_title_append);

$sql_query = "select cast(`cedulas_listado`.`cedula_date_in` as date) AS `ingreso`,count(0) AS `cantidad` from `cedulas_listado` {$digitador_filter} group by `ingreso`";


$progreso_cedulas_data = \k1lib\sql\sql_query($db, $sql_query, TRUE, TRUE);
if ($progreso_data) {
    $progreso_table = new \k1lib\html\foundation\table_from_data();
    $progreso_table->set_data($progreso_cedulas_data);
    $progreso_table->append_to($row3_col2);
}