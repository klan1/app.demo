<?php

namespace k1app;

$send_start = microtime(true);

// Composer lines
define("K1LIB_LANG", "en");
require '../../vendor/autoload.php';

// k1.app start
const APP_MODE = "shell";

//define("APP_MODE", "shell");
include_once "../../bootstrap.php";

// https://elecciones.registraduria.gov.co//e14_cong_2014/e14_divulgacion/CAM/31/001/002/CAM/E14_CAM_X_31_001_002_XX_03_004_X_XXX.pdf
//                         https://visor.digitalizacione14.co/e14_divulgacion/31/001/002/CAM/E14_CAM_X_31_001_002_XX_05_007_X_XXX.pdf
// https://elecciones.registraduria.gov.co//e14_cong_2014/e14_divulgacion/SEN/31/001/002/SEN/E14_SEN_X_31_001_002_XX_03_004_X_XXX.pdf
//                         https://visor.digitalizacione14.co/e14_divulgacion/31/001/002/SEN/E14_SEN_X_31_001_002_XX_05_007_X_XXX.pdf
$send_start = microtime(true);

const E14_YEAR = 2018;

//\k1lib\common\check_on_k1lib();

/**
 * CLI Params
 * %1 Corporacion
 * %2 Mode (wget)
 * %3 Mesa (todas)
 */
if (isset($argv)) {
    //CORPORACION
    if (isset($argv[1])) {
        $codcorporacion = $argv[1];
    } else {
        $codcorporacion = "CAM";
    }
    switch ($codcorporacion) {
//        case "ALC":
//            $cut_pdf = FALSE;
//            $max_page = 2;
//            define("E14_URL_PATTERN", "/%" . DIVIPOL_CODDEPARTAMENTO_FIELD . "%"
//                    . "/%" . DIVIPOL_CODMUNICIPIO_FIELD . "%"
//                    . "/%" . DIVIPOL_CODZONA_FIELD . "%"
//                    . "/{$codcorporacion}"
//                    . "/E14_{$codcorporacion}_X_%" . DIVIPOL_CODDEPARTAMENTO_FIELD . "%_%" . DIVIPOL_CODMUNICIPIO_FIELD . "%_%" . DIVIPOL_CODZONA_FIELD . "%_XX_%" . DIVIPOL_PUESTO_FIELD . "%_%" . DIVIPOL_MESA_FIELD . "%_X_XXX");
//            $municipio = 1;
//            $municipio_path = "";
//            break;
        case "CAM":
            $cut_pdf = TRUE;
            $max_page = 8;
            $pages_cut[1] = null; // L
            $pages_cut[2] = null; // U, CR
            $pages_cut[3] = null; // U, CR
            $pages_cut[4] = null; // U, CR
            $pages_cut[5] = null; // U, CR
            $pages_cut[6] = null; // U, CR
            $pages_cut[7] = null; // U, CR
            $pages_cut[8] = null; // U, CR

            break;
        case "SEN":
            $cut_pdf = TRUE;
            $max_page = 11;
            $pages_cut[1] = null; // L
            $pages_cut[2] = null; // U, CR
            $pages_cut[3] = null; // U, CR
            $pages_cut[4] = null; // U, CR
            $pages_cut[5] = null; // U, CR
            $pages_cut[6] = null; // U, CR
            $pages_cut[7] = null; // U, CR
            $pages_cut[8] = null; // U, CR
            $pages_cut[8] = null; // U, CR
            $pages_cut[9] = null; // U, CR
            $pages_cut[10] = null; // U, CR
            $pages_cut[11] = null; // U, CR
            // https://elecciones.registraduria.gov.co//e14_cong_2014/e14_divulgacion
            // /CAM
            // /31
            // /001
            // /002
            // /CAM
            // /E14_CAM_X_
            // 31_
            // 001_
            // 002_XX_
            // 03_004_X_XXX.pdf

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
//d($argv);



$divipol_sql = "SELECT "
        . DIVIPOL_CODDEPARTAMENTO_FIELD . ","
        . DIVIPOL_CODMUNICIPIO_FIELD . ","
        . DIVIPOL_CODZONA_FIELD . ","
        . DIVIPOL_PUESTO_FIELD . ","
        . DIVIPOL_MESA_FIELD
        . " FROM " . DIVIPOL
        . " WHERE " . DIVIPOL_CODDEPARTAMENTO_FIELD . " = 31 "
//        . " WHERE " . DIVIPOL_CODDEPARTAMENTO_FIELD . " = 31 AND " . DIVIPOL_CODMUNICIPIO_FIELD . " = {$municipio}"
        . " {$mesa_to_download} " // opcional no magico
        . " ORDER BY "
        . DIVIPOL_CODDEPARTAMENTO_FIELD . ","
        . DIVIPOL_CODMUNICIPIO_FIELD . ","
        . DIVIPOL_MESA_FIELD . ","
        . DIVIPOL_CODZONA_FIELD . ","
        . DIVIPOL_PUESTO_FIELD . " ";
//        . "limit 10";
//d($divipol_sql);

$divipolArray = \k1lib\sql\sql_query($db, $divipol_sql);
//\d($divipolArray);
$e14_count = 0;
$e14_null_csv = '';
foreach ($divipolArray as $infoDivipolArray) {
    $e14_count++;
//    $e14_to_save = E14_PATH . "/" . E14_YEAR . "/" . $codcorporacion . "/" . $pdf_filename . ".pdf";
//    $e14_to_save = "../e14/" . E14_YEAR . "/" . $codcorporacion . "/" . $pdf_filename . ".pdf";
    $e14_to_save = e14::create_e14_local_path($codcorporacion, $infoDivipolArray[e14::$departamento_id_field], $infoDivipolArray[e14::$municipio_id_field], $infoDivipolArray[e14::$zona_id_field], $infoDivipolArray[e14::$puesto_id_field], $infoDivipolArray[e14::$mesa_id_field]);
//    d("e14_to_save: {$e14_to_save} \n");
    switch ($script_mode) {
        case "wget":
//            sleep(1);
            $pdf_url = e14::create_e14_registraduria_url($codcorporacion, $infoDivipolArray[e14::$departamento_id_field], $infoDivipolArray[e14::$municipio_id_field], $infoDivipolArray[e14::$zona_id_field], $infoDivipolArray[e14::$puesto_id_field], $infoDivipolArray[e14::$mesa_id_field]);
//            d("pdf_url: {$pdf_url} \n");
//            d($pdf_url);
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
//                d("cmd: {$cmd} \n");
//                exit;
                @shell_exec($cmd);
//exit;
                if (empty(filesize($e14_to_save))) {
                    unlink($e14_to_save);
                    d($e14_to_save . " - Borrado por nulo\n");
                    $e14_null_csv .= implode(',', $infoDivipolArray) . PHP_EOL;
                } else {
                    \d($e14_to_save . " - Descargado\n");
                }
//                sleep(1);
            } else {
                \d($e14_to_save . " - Existia\n");
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
//                                d($cmd);
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
file_put_contents('e14_null.csv', $e14_null_csv);
d("Procesados {$e14_count} E14");
