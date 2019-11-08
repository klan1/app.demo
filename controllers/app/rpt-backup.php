<?php

namespace k1app;

\k1lib\session\session_db::is_logged(TRUE, APP_URL . 'app/log/form/');

// This might be different on your proyect

$sql_query = "SELECT * FROM view_rpt_backp";
if (isset($_GET['capitan_id']) && !empty($_GET['capitan_id'])) {
    $sql_query .= " WHERE capitan_id=\"{$_GET['capitan_id']}\"";
}
$reporte_data = \k1lib\sql\sql_query($db, $sql_query, TRUE, TRUE);

if ($reporte_data) {
    $now = date("Y-m-d_H_i_s");
    $file_name = "CR7-listados-{$_GET['capitan_id']}-" . $now . '.csv';

    ob_end_clean();
    header('Pragma: public');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Cache-Control: private', false); // required for certain browsers 
    header('Content-Type: text/plain');

    header('Content-Disposition: attachment; filename="' . $file_name . '";');
//    header('Content-Transfer-Encoding: binary');
//    header('Content-Length: ' . filesize($file_to_write));

    foreach ($reporte_data as $line) {
        if (!empty($line['folio_scan'])) {
            $line['folio_scan'] = ($line['folio_scan'] != 'folio_scan') ? "https://sie.klan1.net/v1/resources/uploads/listados/" . $line['folio_scan'] : $line['folio_scan'];
        }
        foreach ($line as $field => $value) {
            $line[$field] = mb_convert_encoding($value, 'UTF-8', 'OLD-ENCODING');
        }
        echo implode(",", $line) . "\n";
    }
    exit;
} else {
    echo "Sin datos";
}