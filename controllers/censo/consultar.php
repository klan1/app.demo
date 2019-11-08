<?php

namespace k1app;

if (!isset($_COOKIE['_ga_v'])) {
    setcookie('_ga_v', 0, time() + 60 * 60 * 24 * 30, '/');
}

// This might be different on your proyect

use k1lib\html\template as template;
use k1app\k1app_template as DOM;
use k1lib\session\session_db as session_db;

include 'procuraduria.php';

k1app_template::start_template();
$body = DOM::html()->body();

template::load_template('header');
template::load_template('app-header');
template::load_template('app-footer');

DOM::set_title(3, "Consulta de cedulas desde Registraduria y Procuraduria");

DOM::menu_left()->set_active('nav-censo-2019');

\k1lib\notifications\on_DOM::queue_title('Uso de datos abiertos', 'success');
\k1lib\notifications\on_DOM::queue_mesasage('Los datos de esta consulta provienen de una consulta automatizada y combinada del App InfoVotantes 2019 oficial de la <strong>Registraduria</strong> y del sitio web de la <strong>Procuraduria</strong> para el nombre y antecedentes.', 'success');
\k1lib\notifications\on_DOM::queue_mesasage('Para un servicio de cruce automatizado de grandes cantidades por favor escribir a la Whatsapp <a href="https://wa.me/573183988800">+57-3183988800</a>', 'success');
\k1lib\notifications\on_DOM::queue_mesasage('Pronto tendra la capacidad de subir un archivo plano .csv y descargar el resultado cruzado.', 'success');

/**
 * REQUIRED VARS
 */
$fetch_init_time = microtime(TRUE);

$censo_array[0] = [
    'CEDULA' => 'CEDULA',
    'NOMBRES' => 'NOMBRES',
    'DPTO' => 'DPTO_NOMBRE',
    'MCPIO' => 'MCPIO_NOMBRE',
    'ZONA' => 'ZONA',
    'COMUNA' => 'COMUNA',
    'PUESTO' => 'PUESTO',
    'DIRECCION' => 'DIRECCION',
    'MESA' => 'MESA',
    'ANTECEDENTES' => 'ANTECEDENTES'
];
//$censo_array[0]['date_in'=> 'CONSULTADO';
$user_login = session_db::get_user_login();
/**
 * FORM POST CATCH ADN CENSO QUERY
 */
$max_query_per_ip = 50;
$post = \k1lib\forms\check_all_incomming_vars($_POST);
if (!empty($post)) {
    $guest_abuse = false;
    switch (session_db::get_user_level()) {
        case 'guest':
            $count_ip_querys = \k1lib\sql\sql_query($db_sie, 'SELECT COUNT(*) as NUM_CEDULAS FROM censo_log WHERE ip = "' . $_SERVER['REMOTE_ADDR'] . '"', FALSE, FALSE);
            $query_limit = 50;

            if ($_COOKIE['_ga_v'] >= $query_limit) {
                $guest_abuse = true;
            }
            break;

        case 'censo':
            $count_ip_querys = \k1lib\sql\sql_query($db_sie, 'SELECT COUNT(*) as NUM_CEDULAS FROM censo_log WHERE user_login = "' . $user_login . '"', FALSE, FALSE);
            $query_limit = 5000;
            break;

        default:
            $count_ip_querys = \k1lib\sql\sql_query($db_sie, 'SELECT COUNT(*) as NUM_CEDULAS FROM censo_log WHERE user_login = "' . $user_login . '"', FALSE, FALSE);
            $query_limit = 50000;
            break;
    }

    if ($count_ip_querys['NUM_CEDULAS'] >= $query_limit) {
        \k1lib\notifications\on_DOM::queue_title('Limite de uso', 'alert');
        \k1lib\notifications\on_DOM::queue_mesasage("Este usuario solo pueden realizar {$query_limit} consultas.", 'alert');
        \k1lib\notifications\on_DOM::queue_mesasage("Usuario: " . $user_login, 'alert');
        \k1lib\notifications\on_DOM::queue_mesasage("Consultas: " . $count_ip_querys['NUM_CEDULAS'], 'alert');
    } elseif ($guest_abuse) {
        \k1lib\notifications\on_DOM::queue_title('Posible abuso del servicio', 'alert');
        \k1lib\notifications\on_DOM::queue_mesasage("Este navegador solo pueden realizar {$query_limit} consultas.", 'alert');
    } else {
        switch (session_db::get_user_level()) {
            case 'guest':
                \k1lib\notifications\on_DOM::queue_mesasage("Haz consultado {$count_ip_querys['NUM_CEDULAS']} veces desde la IP {$_SERVER['REMOTE_ADDR']}, para uso sin limites escribe al Whatsapp <a href='https://wa.me/573183988800'>+57-3183988800</a>", 'warning');
                break;

            default:
                \k1lib\notifications\on_DOM::queue_mesasage("Haz consultado {$count_ip_querys['NUM_CEDULAS']} veces desde el usuario: " . $user_login, 'warning');
                break;
        }


        $censo_table = new \k1lib\crudlexs\class_db_table($db_sie, 'censo_divipole');
        $divipole_table = new \k1lib\crudlexs\class_db_table($db_sie, 'divipole');
        $procuraduria_table = new \k1lib\crudlexs\class_db_table($db_sie, 'procuraduria_cache');

        $cedulas = explode(',', $post['cedula-query']);
        if (count($cedulas) > 3) {
            \k1lib\notifications\on_DOM::queue_title('Limite de uso', 'alert');
            \k1lib\notifications\on_DOM::queue_mesasage("Tu actual consulta tiene mas de 3 cedulas simultaneas. Un error no dura para siempre. ^_^", 'alert');
        } else {

            if (!empty($cedulas)) {
                foreach ($cedulas as $key => $cedula) {
                    $nuip_fetch_init_time = microtime(TRUE);

                    $cedula = trim($cedula);
                    if (is_numeric($cedula)) {

                        setcookie('_ga_v', ++$_COOKIE['_ga_v'], time() + 60 * 60 * 24 * 30, '/');

                        $censo_table->set_query_filter(['nuip' => $cedula], TRUE);
                        $censo = $censo_table->get_data(FALSE, FALSE);
                        if ($censo !== FALSE) {
                            $divipole_table->set_query_filter(['divipole_id' => $censo['divipole_id']], TRUE);
                            $divipole_data = $divipole_table->get_data(FALSE, false);
                            $procuraduria_table->set_query_filter(['nuip' => $cedula], TRUE);
//                    $procuraduria_data = $procuraduria_table->get_data(FALSE, false);

                            $procuraduria_data = get_procuraduria_name($db_sie, $cedula, TRUE);

                            $nombre[] = $procuraduria_data['NOMBRE_1'];
                            $nombre[] = $procuraduria_data['NOMBRE_2'];
                            $nombre[] = $procuraduria_data['APELLIDO_1'];
                            $nombre[] = $procuraduria_data['APELLIDO_2'];

                            $censo_array[] = [
                                'CEDULA' => $cedula,
                                'NOMBRES' => implode(' ', $nombre),
                                'DPTO' => $divipole_data['DPTO_NOMBRE'],
                                'MCPIO' => $divipole_data['MCPIO_NOMBRE'],
                                'ZONA' => $divipole_data['ZONA'],
                                'COMUNA' => $divipole_data['COMUNA'],
                                'PUESTO' => $divipole_data['PUESTO_NOMBRE'],
                                'DIRECCION' => $divipole_data['DIRECCION'],
                                'MESA' => $censo['mesa'],
                                'ANTECEDENTES' => $procuraduria_data['antecedentes'],
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
                            \k1lib\sql\sql_insert($db_sie, "censo_log", $censo_log, $error);
                        } else {
                            d($cedula . ': no encontrada o sin informacion de puesto de votacion, aun este app no especifica novedades');
                        }
                    } else {
                        d($cedula . ': No es una cedula valida');
                    }
                    $censo_table->clear_query_filter();
                    $divipole_table->clear_query_filter();
                    $procuraduria_table->clear_query_filter();
                    unset($nombre);
                }
            }
        }
    }
} else {
    $post['cedula-query'] = NULL;
}

//$titulo = new \k1lib\html\h2();
//$titulo->set_value("TITULO 1");
//$titulo->append_to($body->content());

$body->content()->append_h2("CONSULTA DE CENSO");

$censo_div = $body->content()->append_div("consulta-censo");

$form = new \k1lib\html\form("consulta-censo-form");
$input = new \k1lib\html\input('text', 'cedula-query', $post['cedula-query']);
$input->set_attrib('placeholder', 'Permite hasta 10 cedulas, ej: 16000123,81000123,1128447000');
$button = new \k1lib\html\button('consultar', 'button success', NULL, 'submit');

$div_row = new \k1lib\html\foundation\grid_row(2);
$div_row->col(1)->small(12)->medium(10)->append_child($input);
$div_row->col(2)->small(12)->medium(2)->append_child($button);

$div_row->append_to($form);
$form->append_to($censo_div);

if (!empty($censo_array[1])) {
    $table = new \k1lib\html\foundation\table_from_data();

    $table->set_data($censo_array);
    $table->append_to($censo_div);

    $fetch_run_time = round((microtime(TRUE) - $fetch_init_time), 5);
    $censo_div->append_p('La consulta tomo: ' . $fetch_run_time . ' Segundos');
}
