<?php

// http://w3.registraduria.gov.co/e14_divulgacion/AL3100102060004.pdf
// http://w3.registraduria.gov.co/e14_divulgacion/AL 31 001 02 06 0004 .pdf length lenght 

namespace k1app;

const APP_MODE = "shell";

require "../../bootstrap.php";

\k1lib\common\check_on_k1lib();

/**
 * CLI Params
 */
if (isset($argv)) {
    //CORPORACION
    if (isset($argv[1])) {
        $codcorporacion = $argv[1];
    } else {
        $codcorporacion = "ALC";
    }
    switch ($codcorporacion) {
        case "ALC":
            $cut_pdf = FALSE;
            $max_page = 2;
            define("E14_URL_PATTERN", "/%" . DIVIPOL_CODDEPARTAMENTO_FIELD . "%"
                    . "/%" . DIVIPOL_CODMUNICIPIO_FIELD . "%"
                    . "/%" . DIVIPOL_CODZONA_FIELD . "%"
                    . "/{$codcorporacion}"
                    . "/E14_{$codcorporacion}_X_%" . DIVIPOL_CODDEPARTAMENTO_FIELD . "%_%" . DIVIPOL_CODMUNICIPIO_FIELD . "%_%" . DIVIPOL_CODZONA_FIELD . "%_XX_%" . DIVIPOL_PUESTO_FIELD . "%_%" . DIVIPOL_MESA_FIELD . "%_X_XXX");
            $municipio = 1;
            $municipio_path = "";
            break;
        case "CON":
            $cut_pdf = TRUE;
            $max_page = 7;
            $pages_cut[1] = null; // CR
            $pages_cut[2] = null; // L, C
            $pages_cut[3] = null; // U
            $pages_cut[5] = null; // VERDE
            $pages_cut[6] = null; // CD
            define("E14_URL_PATTERN", "/%" . DIVIPOL_CODDEPARTAMENTO_FIELD . "%"
                    . "/%" . DIVIPOL_CODMUNICIPIO_FIELD . "%"
                    . "/%" . DIVIPOL_CODZONA_FIELD . "%"
                    . "/{$codcorporacion}1"
                    . "/E14_{$codcorporacion}_X_%" . DIVIPOL_CODDEPARTAMENTO_FIELD . "%_%" . DIVIPOL_CODMUNICIPIO_FIELD . "%_%" . DIVIPOL_CODZONA_FIELD . "%_XX_%" . DIVIPOL_PUESTO_FIELD . "%_%" . DIVIPOL_MESA_FIELD . "%_X_XXX");
            $municipio = 1;
            $municipio_path = "";
            break;

        default:
            break;
    }
    //MODE
    if (isset($argv[2])) {
        $script_mode = $argv[2];
    } else {
        $script_mode = "wget";
    }
    //MESA Specific
    if (isset($argv[3])) {
        $mesa_to_download = $argv[3];
    } else {
        $mesa_to_download = NULL;
    }
    if (!empty($mesa_to_download)) {
        $mesa_to_download = "AND " . DIVIPOL_MESA_FIELD . " = {$mesa_to_download}";
    }
}
d($argv);

//define("E14_FILE_PATTERN", $codcorporacion . "%" . DIVIPOL_CODDEPARTAMENTO_FIELD . "%%" . DIVIPOL_CODMUNICIPIO_FIELD . "%%" . DIVIPOL_CODZONA_FIELD . "%%" . DIVIPOL_PUESTO_FIELD . "%%" . DIVIPOL_MESA_FIELD . "%");
define("E14_FILENAME_PATTERN", "E14_{$codcorporacion}_X_%" . DIVIPOL_CODDEPARTAMENTO_FIELD . "%_%" . DIVIPOL_CODMUNICIPIO_FIELD . "%_%" . DIVIPOL_CODZONA_FIELD . "%_XX_%" . DIVIPOL_PUESTO_FIELD . "%_%" . DIVIPOL_MESA_FIELD . "%_X_XXX");


$divipol_sql = "SELECT "
        . DIVIPOL_CODDEPARTAMENTO_FIELD . ","
        . DIVIPOL_CODMUNICIPIO_FIELD . ","
        . DIVIPOL_CODZONA_FIELD . ","
        . DIVIPOL_PUESTO_FIELD . ","
        . DIVIPOL_MESA_FIELD
        . " FROM " . DIVIPOL
        . " WHERE CODDEPARTAMENTO = 31 AND CODMUNICIPIO = {$municipio} {$mesa_to_download} "
        . " ORDER BY "
        . DIVIPOL_CODDEPARTAMENTO_FIELD . ","
        . DIVIPOL_CODMUNICIPIO_FIELD . ","
        . DIVIPOL_MESA_FIELD . ","
        . DIVIPOL_CODZONA_FIELD . ","
        . DIVIPOL_PUESTO_FIELD;
//d($divipol_sql);

$divipolArray = \k1lib\sql\sql_query($db, $divipol_sql);
//\d($divipolArray);
$e14_count = 0;
foreach ($divipolArray as $infoDivipolArray) {
    $e14_count++;
    $dep = str_pad($infoDivipolArray[DIVIPOL_CODDEPARTAMENTO_FIELD], DIVIPOL_CODDEPARTAMENTO_LENGTH, "0", STR_PAD_LEFT);
    $mun = str_pad($infoDivipolArray[DIVIPOL_CODMUNICIPIO_FIELD], DIVIPOL_CODMUNICIPIO_LENGTH, "0", STR_PAD_LEFT);
    $zon = str_pad($infoDivipolArray[DIVIPOL_CODZONA_FIELD], DIVIPOL_CODZONA_LENGTH, "0", STR_PAD_LEFT);
    $pue = str_pad($infoDivipolArray[DIVIPOL_PUESTO_FIELD], DIVIPOL_PUESTO_LENGTH, "0", STR_PAD_LEFT);
    $mesa = str_pad($infoDivipolArray[DIVIPOL_MESA_FIELD], DIVIPOL_MESA_LENGTH, "0", STR_PAD_LEFT);

//    d($infoDivipolArray);
//    for ($m = 1; $m <= $infoDivipolArray[DIVIPOL_MESA_FIELD]; $m++) {

    $pdf_url_name = str_replace("%" . DIVIPOL_CODDEPARTAMENTO_FIELD . "%", $dep, E14_URL_PATTERN);
    $pdf_url_name = str_replace("%" . DIVIPOL_CODMUNICIPIO_FIELD . "%", $mun, $pdf_url_name);
    $pdf_url_name = str_replace("%" . DIVIPOL_CODZONA_FIELD . "%", $zon, $pdf_url_name);
    $pdf_url_name = str_replace("%" . DIVIPOL_PUESTO_FIELD . "%", $pue, $pdf_url_name);
    $pdf_url_name = str_replace("%" . DIVIPOL_MESA_FIELD . "%", $mesa, $pdf_url_name);

    $pdf_filename = str_replace("%" . DIVIPOL_CODDEPARTAMENTO_FIELD . "%", $dep, E14_FILENAME_PATTERN);
    $pdf_filename = str_replace("%" . DIVIPOL_CODMUNICIPIO_FIELD . "%", $mun, $pdf_filename);
    $pdf_filename = str_replace("%" . DIVIPOL_CODZONA_FIELD . "%", $zon, $pdf_filename);
    $pdf_filename = str_replace("%" . DIVIPOL_PUESTO_FIELD . "%", $pue, $pdf_filename);
    $pdf_filename = str_replace("%" . DIVIPOL_MESA_FIELD . "%", $mesa, $pdf_filename);

//    $e14_to_save = E14_PATH . "/" . E14_YEAR . "/" . $codcorporacion . "/" . $pdf_filename . ".pdf";
    $e14_to_save = "../e14/" . E14_YEAR . "/" . $codcorporacion . "/{$municipio_path}" . $pdf_filename . ".pdf";

    switch ($script_mode) {
        case "wget":
            $pdf_url = E14_SERVER . $pdf_url_name . ".pdf";
//            d($pdf_url);
//            exit;
            if (!file_exists($e14_to_save)) {
//                \d($e14_to_save);
                static $user_agents = array(
                    0 => "Mozilla/5.0 ;Windows NT 6.1; WOW64; Trident/7.0; rv:11.0; like Gecko",
                    1 => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13',
                    2 => 'Mozilla/5.0 (Windows; U; Windows NT 5.0; en-US; rv:1.7.1.13) Gecko/20080310 Firefox/2.0.0.13',
                    3 => 'Mozilla/5.0 ;Windows NT 6.2; WOW64; rv:27.0; Gecko/20100101 Firefox/27.0',
                    4 => 'Mozilla/5.0 ;Windows NT 6.1; WOW64; rv:26.0; Gecko/20100101 Firefox/27.0',
                );
                $user_agents_index = rand(0, (count($user_agents) - 1));
//    if (count($proxy_list_array) === 0) {
                $cmd = "wget "
//                        . "-c "
                        . "--user-agent=\"{$user_agents[$user_agents_index]}\" "
//                        . "-e  use_proxy=yes -e http_proxy=97.77.104.22:80 "
                        . "--max-redirect=0 "
                        . "--timeout=1.5 "
                        . "--dns-timeout=1.5 "
                        . "--connect-timeout=1.5 "
                        . "--read-timeout=1.5 "
//                        . "--wait=1 "
//                        . "--waitretry=1 "
                        . "--output-document={$e14_to_save}"
                        . " {$pdf_url}";
//                d($cmd);
                @shell_exec($cmd);
//exit;
                if (empty(filesize($e14_to_save))) {
                    unlink($e14_to_save);
                    \d($e14_to_save . " - Borrado por nulo");
                } else {
                    \d($e14_to_save . " - Descargado");
                }
                sleep(1);
            } else {
                \d($e14_to_save . " - Existia");
            }
            break;
        case "pdftk" :
            if ($cut_pdf) {
                if (file_exists($e14_to_save)) {
                    if (!empty(@filesize($e14_to_save))) {
                        foreach ($pages_cut as $p => $value) {
                            $e14_to_save_pages = E14_PATH . "/" . E14_YEAR . "/" . $codcorporacion . "/pages/" . $pdf_filename . "-{$p}-{$max_page}.pdf";
                            if (!file_exists($e14_to_save_pages)) {
                                $cmd = "pdftk '{$e14_to_save}' cat {$p} {$max_page} output '{$e14_to_save_pages}'";
//                            d($cmd);
                                $return = @shell_exec($cmd);
                                if (strstr($return, "Error") !== FALSE) {
                                    continue 2;
                                }
                            } else {
                                \d($e14_to_save_pages . " - WAS THERE");
                                continue 2;
                            }
                            \d($e14_to_save . " - OK");
                        }
                    }
                } else {
//                    d("$e14_to_save es nulo o no existe.");
                }
            }
            break;
        case "clean" :
            if (file_exists($e14_to_save)) {
                $e14_to_save_size = @filesize($e14_to_save);
                if (empty($e14_to_save_size)) {
                    unlink($e14_to_save);
                    d($e14_to_save . " ha sido borrado");
                } else {
                    d($e14_to_save . " - $e14_to_save_size bytes");
                }
            }
            break;
        default:
            break;
    }
//    }
}
d("Procesados {$e14_count} E14");
