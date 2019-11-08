<?php

namespace k1app;

// This might be different on your proyect

use k1lib\html\template as template;
use \k1lib\urlrewrite\url as url;
use k1app\k1app_template as DOM;
use k1lib\notifications\on_DOM as DOM_notifications;
use k1lib\session\session_db as session_db;

\k1lib\session\session_db::is_logged(TRUE, APP_URL . 'app/log/form/');

error_reporting(E_ALL);
ini_set('display_errors', 1);

//include 'db-sie.php';
include 'db-sie.php';
include 'procuraduria.php';

k1app_template::start_template();

$body = DOM::html()->body();

template::load_template('header');
template::load_template('app-header');
template::load_template('app-footer');

DOM::menu_left()->set_active('nav-cedulas');

$db_table_to_use = "cedulas_listado";
$controller_name = "Cedula en listado";

/**
 * ONE LINE config: less codign, more party time!
 */
$controller_object = new \k1lib\crudlexs\controller_base(APP_BASE_URL, $db, $db_table_to_use, $controller_name, 'k1lib-title-3');
$controller_object->set_config_from_class('\k1app\cedula_nombre_check_config_class');

//$controller_object->db_table->set_field_constants(['user_login' => session_db::get_user_login()]);

/**
 * INIT ******
 */
$div = $controller_object->init_board();

//$controller_object->read_url_keys_text_for_list('listados');
$controller_object->read_url_keys_text_for_create('listados');

if ($controller_object->on_board_list()) {
    $controller_object->board_list_object->set_create_enable(TRUE);
}
/**
 * START ******
 */
$controller_object->start_board();

// LIST
if ($controller_object->on_object_list()) {
    $read_url = url::do_url($controller_object->get_controller_root_dir() . "{$controller_object->get_board_read_url_name()}/--rowkeys--/", ["auth-code" => "--authcode--"]);
    $controller_object->board_list_object->list_object->apply_link_on_field_filter($read_url, \k1lib\crudlexs\crudlexs_base::USE_LABEL_FIELDS);
}
if ($controller_object->on_object_read()) {

    $cedula_listado = new \k1lib\crudlexs\class_db_table($db, 'cedulas_listado');
    $cedula_listado->set_query_filter($controller_object->board_read()->read_object->get_row_keys_array(), TRUE);
    $cedula_data = $cedula_listado->get_data(TRUE, true);
    /**
     * Custom Links
     */
    $get_params = ['auth-code' => '--fieldauthcode--'];
    $listado_url = url::do_url(APP_BASE_URL . listados_config_class::ROOT_URL . '/' . listados_config_class::BOARD_READ_URL . '/--customfieldvalue--/', $get_params);
    $controller_object->object_read()->apply_link_on_field_filter($listado_url, ['listado_id'], ['listado_id', 'user_login']);
    /**
     * APLICAR EXISTE EN CENSO
     */
    if (isset($_GET['censo-check'])) {
        if ($_GET['censo-check'] == 1) {
            $controller_object->object_read()->db_table->update_data(['censo_check' => 1], ['nuip_id' => $cedula_data[1]['nuip_id']]);
            \k1lib\html\html_header_go(url::do_url('./', [], TRUE, ['auth-code']));
        } elseif ($_GET['censo-check'] == 0) {
            $controller_object->object_read()->db_table->update_data(['censo_check' => 0], ['nuip_id' => $cedula_data[1]['nuip_id']]);
            \k1lib\html\html_header_go(url::do_url('./', [], TRUE, ['auth-code']));
        }
    }
    if (isset($_GET['nombre-check'])) {
        if ($_GET['nombre-check'] == 1) {
            $controller_object->object_read()->db_table->update_data(['nombre_check' => 1], ['nuip_id' => $cedula_data[1]['nuip_id']]);
            \k1lib\html\html_header_go(url::do_url('./', [], TRUE, ['auth-code']));
        } elseif ($_GET['nombre-check'] == 0) {
            $controller_object->object_read()->db_table->update_data(['nombre_check' => 0], ['nuip_id' => $cedula_data[1]['nuip_id']]);
            \k1lib\html\html_header_go(url::do_url('./', [], TRUE, ['auth-code']));
        }
    }
}
/**
 * EXCEC ******
 */
$controller_object->exec_board();

/**
 * FINISH ******
 */
$controller_object->finish_board();

if ($controller_object->on_board_read()) {
    $related_div = $div->append_div("row k1lib-crudlexs-related-data");

    /**
     * 
     * CONSULTA Y COMPROBACION DE CENSO
     * 
     */
    $related_div->append_h4('INFORMACION DE CENSO 2019');

    $fetch_init_time = microtime(TRUE);
    $user_login = session_db::get_user_login();

    $censo_array[0] = [
        'DPTO' => 'DPTO',
        'MCPIO' => 'MCPIO',
        'ZONA' => 'ZONA',
        'COMUNA' => 'COMUNA',
        'PUESTO' => 'PUESTO',
        'MESA' => 'mesa',
        'DPTO_NOMBRE' => 'DPTO_NOMBRE',
        'MCPIO_NOMBRE' => 'MCPIO_NOMBRE',
        'COMUNA_NOMBRE' => 'COMUNA_NOMBRE',
        'PUESTO_NOMBRE' => 'PUESTO_NOMBRE',
        'DIRECCION' => 'DIRECCION',
    ];

    $cedula = $cedula_data[1]['cedula'];
    $nuip_fetch_init_time = microtime(TRUE);

    // consulta del censo en la tabla de sie2019
    $divipole_table = new \k1lib\crudlexs\class_db_table($db_sie, 'divipole');
    $censo_table = new \k1lib\crudlexs\class_db_table($db_sie, 'censo_divipole');
    $censo_table->set_query_filter(['nuip' => $cedula], TRUE);
    $censo = $censo_table->get_data(FALSE, FALSE);

    if ($censo !== FALSE) {
        // SE trae la info de la divipole asocioada al cendo obtenido
        $divipole_table->set_query_filter(['divipole_id' => $censo['divipole_id']], TRUE);
        $divipole_data = $divipole_table->get_data(FALSE, false);

        $censo_array[] = [
            'DPTO' => $divipole_data['DPTO'],
            'MCPIO' => $divipole_data['MCPIO'],
            'ZONA' => $divipole_data['ZONA'],
            'COMUNA' => $divipole_data['COMUNA'],
            'PUESTO' => $divipole_data['PUESTO'],
            'MESA' => $censo['mesa'],
            'DPTO_NOMBRE' => $divipole_data['DPTO_NOMBRE'],
            'MCPIO_NOMBRE' => $divipole_data['MCPIO_NOMBRE'],
            'COMUNA_NOMBRE' => $divipole_data['COMUNA_NOMBRE'],
            'PUESTO_NOMBRE' => $divipole_data['PUESTO_NOMBRE'],
            'DIRECCION' => $divipole_data['DIRECCION'],
        ];

        $nuip_fetch_run_time = round((microtime(TRUE) - $nuip_fetch_init_time), 5);
        $censo_log = array(
            'cedula' => $cedula,
//        'http_response' => (isset($content1_info['http_code'])) ? $content1_info['http_code'] : -1,
//        'proxy' => $actual_proxy,
            'script_time' => $nuip_fetch_run_time,
            'IP' => (isset($_SERVER['REMOTE_ADDR'])) ? $_SERVER['REMOTE_ADDR'] : "127.0.0.1",
            'agent' => (isset($_SERVER['HTTP_USER_AGENT'])) ? $_SERVER['HTTP_USER_AGENT'] : "PHP (CLI)",
            'datetime' => date("Y-m-d H:i:s"),
            'user_login' => $user_login,
        );
        $error = NULL;
        \k1lib\sql\sql_insert($db, "censo_log", $censo_log, $error);
    } else {
        $related_div->append_p($cedula . ': no encontrada o sin informacion de puesto de votacion, aun este app no especifica novedades');
    }

    if (!empty($censo_array[1])) {
        $table = new \k1lib\html\foundation\table_from_data();
        error_reporting(0);
        $table->set_data($censo_array);
        $table->append_to($related_div);

        $fetch_run_time = round((microtime(TRUE) - $fetch_init_time), 5);
        $related_div->append_p('La consulta tomo: ' . $fetch_run_time . ' Segundos');
    }

    /**
     * 
     * NO SE HA CALIFICADO EL CENSO AUN Y SI HAY DATOS DE CENSO, SE APLICAN
     */
    if ($cedula_data[1]['censo_check'] === NULL) {
        if (!empty($censo_array[1]) && $cedula_data[1]['censo_check'] === NULL) {
            $controller_object->object_read()->db_table->update_data($censo_array[1], $controller_object->board_read()->read_object->get_row_keys_array());
            $controller_object->object_read()->db_table->update_data(['censo_check' => 1], $controller_object->board_read()->read_object->get_row_keys_array());
            \k1lib\html\html_header_go(url::do_url('./', [], TRUE, ['auth-code']));
        }
    }
    /**
     * SI PREVIAMENTE SE HAD DICHO QUE NO, SE DA EL MENSAJE CORRESPONDIENTE
     */
    if ($cedula_data[1]['censo_check'] == 0) {
        if (empty($censo_array[1])) {
            /**
             * NO SE ENCUENTRA EN CENSO
             */
//            $controller_object->object_read()->db_table->update_data(['censo_check' => 0], ['nuip_id' => $cedula_data[1]['nuip_id']]);
//        $registraduria_button = new \k1lib\html\a('https://wsp.registraduria.gov.co/censo/consultar/', 'Abrir CENSO en Registraduria', 'censo', 'button');
            $callout_registraduria = new \k1lib\html\foundation\callout(NULL, "La cédula no se encuentra en el censo local", TRUE, 'warning');
            $callout_registraduria->append_p("Se recomienda usar el sitio de la Registraduria para comprobar este numero.");
            $callout_registraduria->append_to($related_div);
        }
        $callout_registraduria = new \k1lib\html\foundation\callout(NULL, "La cédula fue desconfirmada en el censo", TRUE, 'alert');
        $callout_registraduria->append_to($related_div);
        $related_div->append_a(url::do_url('./', ['censo-check' => 1]), ' Confirmar censo', '_self', 'button success fi-check')->set_attrib('onclick', "javascript:return confirm('Estas seguro?')");
    }

    if ($cedula_data[1]['censo_check'] === '1') {
        $callout_registraduria = new \k1lib\html\foundation\callout(NULL, "La cédula fue confirmada en el censo", TRUE, 'success');
        $callout_registraduria->append_to($related_div);
        $related_div->append_a(url::do_url('./', ['censo-check' => 0]), 'Desconfirmar censo', '_self', 'button alert')->set_attrib('onclick', "javascript:return confirm('Estas seguro?')");
    }
    $related_div->append_a('https://wsp.registraduria.gov.co/censo/consultar/', ' Consultar Registraduria', 'procuraduria', 'button fi-link');

    /**
     * 
     * COMPROBACION DEL NOMRE EN LA PROCURADURIA CON ANTECEDENTES
     * 
     */
    $related_div->append_h4('Nombres desde procuraduria.gov.co');
    $datos_procuraduria = get_procuraduria_name($db_sie, $controller_object->object_read()->get_db_table_data()[1]['cedula']);

    if ($datos_procuraduria === FALSE) {
        /**
         * NO SE PUDO ABRIR LA PAGINA DE LA PROCURADURIA
         */
        $callout_procuraduria = new \k1lib\html\foundation\callout(NULL, "El sitio web procuraduria.gov.co no ha retornado datos, por favor recarga la página.", TRUE, 'alert');
        $procuraduria_button = new \k1lib\html\a(url::do_url('./', [], TRUE, ['auth-code']), 'Recargar', '_self', 'button warning');
        $callout_procuraduria->append_div()->append_child($procuraduria_button);
        $callout_procuraduria->append_to($related_div);
    } else {
        /**
         * SE OBTUVO CORRECTAMENTE EL NOMBRE
         */
        if (!empty($datos_procuraduria['NOMBRE_1'])) {
            $related_div->append_p("<strong>{$datos_procuraduria['NOMBRE_1']} {$datos_procuraduria['NOMBRE_2']} {$datos_procuraduria['APELLIDO_1']} {$datos_procuraduria['APELLIDO_2']}</strong> - {$datos_procuraduria['ANTECEDENTES']}");
        } else {
            /**
             * LA PROCURADURIA NO TIENE EL NOMBRE REGISTRADO
             */
            $callout_procuraduria = new \k1lib\html\foundation\callout(NULL, "La cedula no se encuentra registrada en procuraduria.gov.co.", TRUE, 'warning');
            $callout_procuraduria->append_to($related_div);

            if ($cedula_data[1]['censo_check'] == 1 && $cedula_data[1]['nombre_check'] === NULL) {
                $controller_object->object_read()->db_table->update_data(['nombre_check' => 1], ['nuip_id' => $cedula_data[1]['nuip_id']]);
                $callout_procuraduria_checked = new \k1lib\html\foundation\callout(NULL, "Se confirma el nombre de la cedula por no exisitir en la procuraduria y existir en el censo", TRUE, 'warning');
                $callout_procuraduria_checked->append_to($related_div);
            }
            if ($cedula_data[1]['censo_check'] == 0 && $cedula_data[1]['nombre_check'] === NULL) {
                $controller_object->object_read()->db_table->update_data(['nombre_check' => 0], ['nuip_id' => $cedula_data[1]['nuip_id']]);
                $callout_procuraduria_checked = new \k1lib\html\foundation\callout(NULL, "Se desconfirma el nombre de la cedula por no exisitir en la procuraduria ni en el censo", TRUE, 'warning');
                $callout_procuraduria_checked->append_to($related_div);
            }
            if ($cedula_data[1]['nombre_check'] === NULL) {
//                \k1lib\html\html_header_go(url::do_url('./', [], TRUE, ['auth-code']));
            }
        }
    }
    if ($cedula_data[1]['nombre_check'] === '1') {
        if (empty($cedula_data[1]['NOMBRE_1'])) {
            unset($datos_procuraduria['nuip']);
            unset($datos_procuraduria['fecha_ingreso']);
            unset($datos_procuraduria['date_in']);
            $controller_object->object_read()->db_table->update_data($datos_procuraduria, $controller_object->board_read()->read_object->get_row_keys_array(), $error, $sql);
            \k1lib\html\html_header_go(url::do_url('./', [], TRUE, ['auth-code']));
        }

        $callout_registraduria = new \k1lib\html\foundation\callout(NULL, "El nombre fue confirmado como correcto", TRUE, 'success');
        $callout_registraduria->append_to($related_div);
        $related_div->append_a(url::do_url('./', ['nombre-check' => 0]), 'Nombre NO coincide', '_self', 'button alert')->set_attrib('onclick', "javascript:return confirm('Estas seguro?')");
    } elseif ($cedula_data[1]['nombre_check'] === '0') {
        if (!empty($cedula_data[1]['NOMBRE_1'])) {
            $blank_data = [
                'NOMBRE_1' => $full_name[1],
                'NOMBRE_2' => $full_name[2],
                'APELLIDO_1' => $full_name[3],
                'APELLIDO_2' => $full_name[4],
                'ANTECEDENTES' => $antecedentes[1]
            ];
            $controller_object->object_read()->db_table->update_data($blank_data, $controller_object->board_read()->read_object->get_row_keys_array(), $error, $sql);
            \k1lib\html\html_header_go(url::do_url('./', [], TRUE, ['auth-code']));
        }

        $callout_registraduria = new \k1lib\html\foundation\callout(NULL, "El nombre fue confirmado como incorrecto", TRUE, 'alert');
        $callout_registraduria->append_to($related_div);
        $related_div->append_a(url::do_url('./', ['nombre-check' => 1]), ' Nombre SI coincide', '_self', 'button success fi-check')->set_attrib('onclick', "javascript:return confirm('Estas seguro?')");
    } elseif ($cedula_data[1]['nombre_check'] === NULL) {
        $related_div->append_a(url::do_url('./', ['nombre-check' => 1]), ' Nombre SI coincide', '_self', 'button success fi-check')->set_attrib('onclick', "javascript:return confirm('Estas seguro?')");
        $related_div->append_a(url::do_url('./', ['nombre-check' => 0]), 'Nombre NO coincide', '_self', 'button alert')->set_attrib('onclick', "javascript:return confirm('Estas seguro?')");
    }
    $related_div->append_a('http://xyz.verifique.se/app/funcion03.php?c=' . $cedula_data[1]['cedula'] . '&t=C%C3%A9dula+Ciudadan%C3%ADa', ' Consultar Procuraduria', 'procuraduria', 'button fi-link');


    /**
     * Nombre internal check
     */
//    if (empty($datos_procuraduria['NOMBRE_1'])) {
//        $nombres_db_table = new \k1lib\crudlexs\class_db_table($db, "nuip_nombres");
//        $nombres_db_table->set_query_limit(0, 1);
//        $controller_object->board_read_object->set_related_show_all_data(FALSE);
//        $controller_object->board_read_object->set_related_show_new(FALSE);
//        $related_list = $controller_object->board_read_object->create_related_list($nombres_db_table, NULL, "Comprobacion de nombre", NULL, NUll, NULL, NULL, FALSE, FALSE, ['nuip' => $cedula_data[1]['cedula']]);
//
//        $related_list->append_to($related_div);
//    }
}

$body->content()->append_child($div);
