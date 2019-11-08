<?php

namespace k1app;

function get_procuraduria_name(\k1lib\db\PDO_k1 $db, $cedula, $just_local = FALSE) {
    $cache_table = new \k1lib\crudlexs\class_db_table($db, 'procuraduria_cache');

    $cache_table->set_query_filter(['nuip' => $cedula], TRUE);
    $cache_result = $cache_table->get_data(FALSE);

    $uptodate = null;
    if (!empty($cache_result)) {
        $result_timestamp = strtotime('+5 days', strtotime($cache_result['date_in']));
        $today_timestamp = time();
//        d($cache_result);
        if (($result_timestamp > $today_timestamp) || $just_local) {
            $uptodate = true;
//            d('up to date');
            return $cache_result;
        } else {
//            d('oudated');
            $uptodate = false;
        }
    } else {
        if ($just_local) {
            $empty_data = [
                'nuip' => $cedula,
                'NOMBRE_1' => null,
                'ANTECEDENTES' => 'SIN CONSULTA A LA PROCURADURIA'
            ];
            return $empty_data;
        }
//        d('no cache');
    }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
    curl_setopt($ch, CURLOPT_TIMEOUT, 6);
    curl_setopt($ch, CURLOPT_URL, "https://www.procuraduria.gov.co/CertWEB/Certificado.aspx?tpo=1");
    $headers = [
        'Referer: https://www.procuraduria.gov.co/CertWEB/Certificado.aspx?tpo=1',
        'Origin: https://www.procuraduria.gov.co',
        'Content-Type: application/x-www-form-urlencoded',
        'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
        'sUer-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/64.0.3282.140 Safari/537.36 Edge/17.17134',
    ];
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS,
            http_build_query(array(
        '__VIEWSTATEGENERATOR' => 'D8335CE7',
        '__EVENTARGUMENT' => '',
        'tpo' => '1',
        '__VIEWSTATE' => '/wEPDwUJLTU5NTU5MDcyDxYCHgpJZFByZWd1bnRhBQE0FgICAw9kFgwCAQ8PFgIeBFRleHQFGENvbnN1bHRhIGRlIGFudGVjZWRlbnRlc2RkAg0PFgIeB1Zpc2libGVoFgQCAQ9kFgICAQ8QZGQWAWZkAgMPZBYCAgEPEGRkFgBkAg8PDxYCHwEFPsK/RXNjcmliYSBsb3MgdHJlcyBwcmltZXJvcyBkaWdpdG9zIGRlbCBkb2N1bWVudG8gYSBjb25zdWx0YXI/ZGQCGA8PFgIfAmhkZAIgDw8WAh8BBUhGZWNoYSBkZSBjb25zdWx0YToganVldmVzLCBvY3R1YnJlIDA0LCAyMDE4IC0gSG9yYSBkZSBjb25zdWx0YTogMTI6Mzg6MzFkZAIkDw8WAh8BBQdWLjAuMC40ZGQYAQUeX19Db250cm9sc1JlcXVpcmVQb3N0QmFja0tleV9fFgEFDEltYWdlQnV0dG9uMScpd9RXIowP93E6OmYcJg4Mp3qC',
        'txtNumID' => $cedula,
        'btnConsultar' => 'Consultar',
        'txtRespuestaPregunta' => substr($cedula, 0, 3),
        '__EVENTTARGET' => '',
        '__EVENTVALIDATION' => '/wEWCgLk0vizAQL8kK+TAQLwkOOQAQLvkOOQAQLxkOOQAQL0kOOQAQK8zP8SAtLCmdMIAsimk6ECApWrsq8IcEGeP77ilFjLKbgNg7v8aBywrm4=',
        'ddlTipoID' => '1',
    )));

    // Receive server response ...
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $server_output = curl_exec($ch);
    curl_close($ch);

    if ($server_output !== FALSE) {
        $full_name = '';
        preg_match("#<span>([\w\W]*?)</span><span>([\w\W]*?)</span><span>([\w\W]*?)</span><span>([\w\W]*?)</span>#", $server_output, $full_name);

        $antecedentes = '';
        preg_match("#<h2>([\w\W]*?)</h2>#", $server_output, $antecedentes);

        if (!empty($full_name)) {
            $data = [
                'NOMBRE_1' => $full_name[1],
                'NOMBRE_2' => $full_name[2],
                'APELLIDO_1' => $full_name[3],
                'APELLIDO_2' => $full_name[4],
                'ANTECEDENTES' => $antecedentes[1]
            ];
            if ($uptodate === false) {
                $data['date_in'] = date('Y-m-d H:i:s');
                $cache_table->update_data($data, ['nuip' => $cedula]);
            }
            if ($uptodate === null) {
                $cache_table->insert_data(array_merge(['nuip' => $cedula], $data));
            }
            return $data;
        } else {
            $empty_data = [
                'nuip' => $cedula,
                'NOMBRE_1' => null,
                'ANTECEDENTES' => 'SIN RESULTADO EN LA PROCURADURIA',
            ];
            if (empty($cache_result)) {
                $cache_table->insert_data($empty_data);
            } else {
                $empty_data['date_in'] = date('Y-m-d H:i:s');
                $cache_table->update_data($empty_data, ['nuip' => $cedula]);
            }
            return $empty_data;
        }
    } else {
        if (!empty($cache_result)) {
            return $cache_result;
        } else {
            $empty_data = [
                'nuip' => $cedula,
                'NOMBRE_1' => null,
                'ANTECEDENTES' => 'LA PAGINA DE LA REGISTRADURIA NO ESTA EN LINEA'
            ];
            return $empty_data;
        }
    }
}
