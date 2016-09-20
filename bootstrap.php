<?php

/*
 * Autor: Alejandro Trujillo J.
 * Copyright: Klan1 Network - 2010-2011
 *
 * TODO: Implement the file storage engine
 * TODO: Make a session manager to know is some one has return from some time
 *
 */

namespace k1app;

use \k1lib\session\session_db as session_db;
use \k1lib\templates\temply as temply;

header('Content-Type: text/html; charset=utf-8');

$app_init_time = microtime(TRUE);

const IN_K1APP = TRUE;

/*
 * INCLUDING ALL THE NECESSARY FILES
 */
require_once 'settings/path-settings.php';
require_once 'settings/config.php';

/*
 * DB CONNECTION
 */
if (\k1lib\db\handler::is_enabled()) {
    try {
        // WFT - This is for ?
        //define("PDO_DBNAME", 10113);
        $db = new \k1lib\db\handler();
        $db->set_verbose_level(APP_VERBOSE);
    } catch (\PDOException $e) {
        //sleep(10);
        trigger_error($e->getMessage(), E_USER_ERROR);
    }
    $db->exec("set names utf8");
}

/*
 * APP START
 */
/**
 * @var \k1lib\session\session_db
 */
$app_session = new \k1lib\session\session_db($db);
$app_session->start_session();
$app_session->load_logged_session_db();

/*
 * MANAGE THE URL REWRITING 1st (0 index) level
 */
$url_controller = \k1lib\urlrewrite\url::set_url_rewrite_var(0, "url_section", TRUE);
if (!$url_controller) {
    $url_controller = "index";
}

/*
 * Error messaging form 
 */
if (isset($_GET['error']) || !empty($_GET['error'])) {
    $app_error = \k1lib\forms\check_single_incomming_var($_GET['error']);
} else {
    $app_error = NULL;
}

/*
 * CALLING THE MODULE OR NOTHIG IF IS AN AJAX CALL
 */
switch (\k1app\APP_MODE) {
    case 'web':
        // Start the HTML DOM object
        \k1lib\html\DOM::start(K1LIB_LANG);

//        d(\k1lib\controllers\load_controller($url_controller, APP_CONTROLLERS_PATH));
        require temply::load_template("init", APP_TEMPLATE_PATH . '/scripts');
        require \k1lib\controllers\load_controller($url_controller, APP_CONTROLLERS_PATH);
//        require temply::load_template("end", APP_TEMPLATE_PATH . '/scripts');
//        \k1lib\output_buffer\end_app(TRUE);
        $app_run_time = round((microtime(TRUE) - $app_init_time), 5);
        if (temply::is_place_registered("footer_app_info")) {
            temply::set_place_value("footer_app_info", "Runtime: {$app_run_time} Seg - K1.lib V" . \k1lib\VERSION);
        }

        \k1lib\html\DOM::html()->generate(TRUE);
        break;
    case 'ajax':
        // do nothing, yet
        break;
    case 'shell':
        // do nothing, yet
        break;
    default:
        \k1lib\common\show_error('No \k1app\APP_MODE defined', __FILE__);
        break;
}

if (temply::is_enabled()) {
    temply::end(\k1app\APP_MODE);
}
// APP END, sweet... it isn't ?