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

use k1lib\PROFILER as PROFILER;

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
        $db = new \k1lib\db\handler();
        $db->set_verbose_level(APP_VERBOSE);
    } catch (\PDOException $e) {
        trigger_error($e->getMessage(), E_USER_ERROR);
    }
    $db->exec("set names utf8");
}

/*
 * MANAGE THE URL REWRITING 1st (0 index) level
 */
$url_controller = \k1lib\urlrewrite\url::set_url_rewrite_var(0, "url_section", TRUE);
if (!$url_controller) {
    $url_controller = "index";
}

/**
 * TEMPLATE AND CONTROLLER LOAD
 */
// controller load
require \k1lib\controllers\load_controller($url_controller, APP_CONTROLLERS_PATH);