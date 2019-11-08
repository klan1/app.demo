<?php

namespace k1app;

// This might be different on your proyect

use k1lib\html\template as template;
use \k1lib\urlrewrite\url as url;
use k1app\k1app_template as DOM;
use k1lib\session\session_db as session_db;

\k1lib\session\session_db::is_logged(TRUE, APP_URL . 'ch-2019/log/form/');

k1app_template::start_template();

$body = DOM::html()->body();

template::load_template('header');
template::load_template('app-header-ch-2019');
template::load_template('app-footer');

DOM::menu_left()->set_active('nav-firmas');

$db_table_to_use = "listados";
$controller_name = "Listados para elecciones 2019";

/**
 * ONE LINE config: less codign, more party time!
 */
$controller_object = new \k1lib\crudlexs\controller_base(APP_BASE_URL, $db, $db_table_to_use, $controller_name, 'k1lib-title-3');
$controller_object->set_config_from_class('\k1app\listados_config_class');


/**
 * ALL READY, let's do it :)
 */
$div = $controller_object->init_board();

//$controller_object->read_url_keys_text_for_list('referrer_table_caller');
//$controller_object->read_url_keys_text_for_create('referrer_table_caller');

if ($controller_object->on_board_list()) {
    $controller_object->board_list_object->set_create_enable(TRUE);
    if (session_db::check_user_level(['user'])) {
        d("Solo puedes ver los listados digitados por ti");
        $controller_object->db_table->set_field_constants(['user_login' => session_db::get_user_login()], TRUE);
//        $controller_object->object_list()->db_table->set_query_filter(['user_login' => 'prueba'], TRUE);
    }
}

$controller_object->start_board();

// LIST
if ($controller_object->on_object_list()) {
    $read_url = url::do_url($controller_object->get_controller_root_dir() . "{$controller_object->get_board_read_url_name()}/--rowkeys--/", ["auth-code" => "--authcode--"]);
    $controller_object->board_list_object->list_object->apply_link_on_field_filter($read_url, \k1lib\crudlexs\crudlexs_base::USE_LABEL_FIELDS);
}

// CREATE
if ($controller_object->on_board_create()) {
    $controller_object->db_table->set_field_constants(['user_login' => session_db::get_user_login()]);
}


$controller_object->exec_board();

$controller_object->finish_board();

/**
 * IMPORT CODE BLOCK
 */
if ($controller_object->on_board_read()) {
    if (session_db::check_user_level(['god'])) {
        $import_form = new \k1lib\html\form();
        $import_form->append_to($div);

        $import_form->append_child(
                new \k1lib\html\input('file', 'import-file', '')
        );

        $import_form->append_child(
                new \k1lib\html\input('submit', 'send', 'Enviar')
        );

        if (isset($_POST["send"])) {
            if (isset($_FILES["import-file"])) {

                //if there was an error uploading the file
                if ($_FILES["import-file"]["error"] > 0) {
                    echo "Return Code: " . $_FILES['import-file']["error"] . "<br />";
                } else {
                    //Print file details
                    $ext = strtolower(end(explode('.', $_FILES['import-file']['name'])));

                    // check the file is a csv
                    if ($ext === 'csv') {

                        if (($handle = fopen($_FILES['import-file']["tmp_name"], 'r')) !== FALSE) {
                            // necessary if a large csv file
                            $cl_table = new \k1lib\crudlexs\class_db_table($db, "cedulas_listado");
                            set_time_limit(0);
                            $row = 0;

                            while (($data = fgetcsv($handle, 1000, ';')) !== FALSE) {
                                $row++;
                                if ($row === 1) {
                                    $headers = $data;
                                    continue;
                                }
                                $insert_data = NULL;
                                foreach ($data as $key => $value) {
                                    $field_name = \preg_replace("/[^A-Za-z0-9_]/", '', $headers[$key]);
                                    if (empty($field_name)) {
                                        continue;
                                    }
                                    $insert_data[$field_name] = trim($value);
                                }
                                $cleaned_insert_data = \k1lib\common\clean_array_with_guide($insert_data, $cl_table->get_db_table_config());
                                $error = null;
                                if ($cl_table->insert_data($insert_data, $error)) {
                                    
                                } else {
                                    d($error);
                                }
                            }
                            fclose($handle);
                            $sql = "UPDATE cedulas_listado
                                INNER JOIN k1app_sie2019.censo ON cedulas_listado.cedula = k1app_sie2019.censo.nuip 
                                SET cedulas_listado.censo_check = 1";
                            $db->exec($sql);
                        }
                    }
                }
            } else {
                echo "No file selected <br />";
            }
        }
    }

    $related_div = $div->append_div("row k1lib-crudlexs-related-data");
    /**
     * Related list
     */
    $related_db_table = new \k1lib\crudlexs\class_db_table($db, "cedulas_listado");
//    $controller_object->board_read_object->set_related_show_all_data(FALSE);
//    $controller_object->board_read_object->set_related_show_new(FALSE);
//    $controller_object->board_read_object->set_related_custom_field_labels(['cedula']);
    $controller_object->board_read_object->set_related_do_pagination(true);
    $controller_object->board_read_object->set_related_rows_to_show(25);
    $related_list = $controller_object->board_read_object->create_related_list($related_db_table, ['cedula'], "Cedulas del listado", cedulas_listados_config_class::ROOT_URL, cedulas_listados_config_class::BOARD_CREATE_URL, cedulas_listados_config_class::BOARD_READ_URL, cedulas_listados_config_class::BOARD_LIST_URL, FALSE);
    $related_list->append_to($related_div);
}

$body->content()->append_child($div);
