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
use k1lib\PROFILER as PROFILER;
use k1app\k1app_template as DOM;

PROFILER::start();

header('Content-Type: text/html; charset=utf-8');

const IN_K1APP = TRUE;

/*
 * INCLUDING ALL THE NECESSARY FILES
 */
require_once 'settings/path-settings.php';
require_once 'settings/config.php';

require_once APP_TEMPLATE_PATH . '/definition.php';
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
$app_session = new session_db($db);
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
        require temply::load_template("init", APP_TEMPLATE_PATH . '/scripts');
        require \k1lib\controllers\load_controller($url_controller, APP_CONTROLLERS_PATH);

        require temply::load_template("verbose-output", APP_TEMPLATE_PATH);
        require temply::load_template("end", APP_TEMPLATE_PATH . '/scripts');

        echo DOM::generate();
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
