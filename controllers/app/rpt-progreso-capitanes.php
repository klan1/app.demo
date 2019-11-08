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

DOM::set_title(3, K1APP_TITLE);

DOM::menu_left()->set_active('nav-rpt-progreso-capitanes');

$filter_url_value = url::set_url_rewrite_var(url::get_url_level_count(), 'filter_url_value', FALSE);
$filter_data_url_value = url::set_url_rewrite_var(url::get_url_level_count(), 'digitador_url_value', FALSE);

$content->append_h1("Progreso de capitanes en Cali");
$content->set_class("tablero");

$content->append_a('./?download-rpt', 'Descargar reporte', '_self', 'button');

$sql_query = "SELECT * FROM view_capitanes_progreso";

$reporte_data = \k1lib\sql\sql_query($db, $sql_query, TRUE, TRUE);


if ($reporte_data) {

    if (isset($_GET['download-rpt'])) {
        \k1lib\xml\do_xml($reporte_data, TRUE, 'rpt-progreso-capitanes-' . time() . '.xml');
    } else {
        $progreso_digitadores_table = new \k1lib\html\foundation\table_from_data();
//        $progreso_digitadores_table->set_class('k1lib-crudlexs-list responsive-card-table unstriped')->append_to($content);
        $progreso_digitadores_table->set_class('k1lib-crudlexs-list')->append_to($content);

        $progreso_digitadores_table->set_data($reporte_data);
        $rpt_link = new \k1lib\html\a("/v1/app/rpt-backup/?capitan_id={{field:capitan_id}}", "{{field:capitan_id}}");
        $progreso_digitadores_table->insert_tag_on_field($rpt_link, ['capitan_id']);
    }
}