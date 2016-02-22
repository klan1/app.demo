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

use \k1lib\session\session_plain as k1lib_session;
use \k1lib\templates\temply as temply;

header('Content-Type: text/html; charset=utf-8');

$app_init_time = microtime(TRUE);

const IN_K1APP = TRUE;

/*
 * INCLUDING ALL THE NECESSARY FILES
 */
define("K1LIB_LANG", "en");
if (file_exists('../k1.lib/lastest/init.php')) {
    include_once '../k1.lib/lastest/init.php';
} else {
    require_once 'k1lib-init.php';
}
require_once 'path-settings.php';
require_once 'config.php';

/*
 * APP START
 */
//\k1lib\output_buffer\start_app();
k1lib_session::start_session();
k1lib_session::load_logged_session();

//\k1lib\session\start_app_session();

/*
 *  MAKE THE CONNECTION TO MEMCACHE SERVER
 */
//if (USE_MEMCACHE) {
//    $memcache = new Memcache;
//    $memcache_connected = $memcache->connect(MEMCACHE_SERVER, MEMCACHE_PORT);
//}
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
 * MANAGE THE URL REWRITING 1st (0 index) level
 */
$url_controller = \k1lib\urlrewrite\url_manager::set_url_rewrite_var(0, "url_section", TRUE);
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
//        d(\k1lib\controllers\load_controller($url_controller, APP_CONTROLLERS_PATH));
        require temply::load_template("init", APP_TEMPLATE_PATH . '/scripts');
        require \k1lib\controllers\load_controller($url_controller, APP_CONTROLLERS_PATH);
        require temply::load_template("end", APP_TEMPLATE_PATH . '/scripts');
//        \k1lib\output_buffer\end_app(TRUE);
        $app_run_time = round((microtime(TRUE) - $app_init_time), 5);
        if (temply::is_place_registered("footer_app_info")) {
            temply::set_place_value("footer_app_info", "Runtime: {$app_run_time} Seg - K1.lib V" . \k1lib\VERSION);
        }


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