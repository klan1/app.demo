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

/**
 * AUTOLOAD FOR APP CLASES
 */
spl_autoload_register(function($className) {
    $className = str_replace("\\", DIRECTORY_SEPARATOR, $className);
    $file_to_load = APP_CLASSES_PATH . $className . '.php';
    if (file_exists($file_to_load)) {
        include_once $file_to_load;
    }else{
//        error_reporting(E_ALL);
        trigger_error($className . ' do not fount to autoload', E_USER_NOTICE);
    }
});


require_once APP_TEMPLATE_PATH . '/definition.php';

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
