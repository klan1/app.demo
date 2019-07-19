<?php

namespace k1app;

// MAIN PATH
define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT']);

$server_name = $_SERVER['SERVER_NAME'];

/**
 * TEMPLATE NAME
 */
// BETTER ALL FOR SITE AND SPECIFIC TO CONTROL PANEL
if ((strstr($_SERVER['REQUEST_URI'], '/site/') !== FALSE) || (strstr($_SERVER['REQUEST_URI'], '/get-ecard/') !== FALSE)) {
    $app_template = 'frontend';
} else {
    $app_template = 'k1phphtml';
}

// AUTO CONFIGURATED PATHS
define('APP_ROOT', str_replace('\\', '/', dirname(dirname(__FILE__))));
define('APP_DIR', basename(APP_ROOT) . '/');
define('APP_DOMAIN', $server_name . (( $_SERVER['SERVER_PORT'] != '80') ? ':' . $_SERVER['SERVER_PORT'] : ''));


define('APP_CONTROLLERS_PATH', APP_ROOT . '/controllers/');
define('APP_CLASSES_PATH', APP_ROOT . '/classes/');

const APP_CONTROLLERS_PATH = \APP_CONTROLLERS_PATH;

define('APP_VIEWS_PATH', APP_ROOT . '/views/');

const APP_VIEWS_PATH = \APP_VIEWS_PATH;

define('APP_VIEWS_CRUD_PATH', APP_VIEWS_PATH . '/k1lib.crud/');
define('APP_RESOURCES_PATH', APP_ROOT . '/resources/');
define('APP_SETTINGS_PATH', APP_ROOT . '/settings/');
define('APP_UPLOADS_PATH', APP_RESOURCES_PATH . 'uploads/');
define('APP_SHELL_SCRIPTS_PATH', APP_RESOURCES_PATH . '/shell-scripts/');
define('APP_TEMPLATE_PATH', APP_RESOURCES_PATH . '/template/' . $app_template . '/');
define('APP_FONTS_PATH', APP_RESOURCES_PATH . 'fonts/');

/**
 * COMPOSER
 */
define('COMPOSER_PACKAGES_PATH', APP_ROOT . 'vendor/');
/**
 * BOWER
 */
define('BOWER_PACKAGES_PATH', APP_ROOT . 'bower_components/');

// INCLUDES PATH ADDITION
set_include_path(APP_SETTINGS_PATH . PATH_SEPARATOR . APP_RESOURCES_PATH . '/includes' . PATH_SEPARATOR . get_include_path());

// AUTO CONFIGURATED URLS 
if (APP_MODE != 'shell') {

    /**
     * If this error is trigger you should set by hand the CONST: APP_BASE_URL
     * with your personal configuration.
     */
    $app_base_url = dirname(substr($_SERVER['SCRIPT_FILENAME'], strlen(\DOCUMENT_ROOT))) . '/';
    if ($app_base_url == '//') {
        $app_base_url = '/';
    }
    define('APP_BASE_URL', $app_base_url);

//    define('APP_DOMAIN_URL', (\k1lib\common\get_http_protocol() . '://') . \APP_DOMAIN);
    if ((strstr($_SERVER['SERVER_NAME'], 'somoscausa.org') === FALSE) && (strstr($_SERVER['SERVER_NAME'], 'klan1.net') === FALSE)) {
        define('APP_DOMAIN_URL', 'http://' . \APP_DOMAIN);
    } else {
        define('APP_DOMAIN_URL', 'https://' . \APP_DOMAIN);
    }
    define('APP_URL', APP_DOMAIN_URL . APP_BASE_URL);
    define('APP_LOGIN_URL', APP_URL . 'log/form/');
    define('APP_HOME_URL', APP_URL);
    define('APP_CONTROLLERS_URL', APP_URL . 'controllers/');
    define('APP_VIEWS_URL', APP_URL . 'views/');
    define('APP_RESOURCES_URL', APP_URL . 'resources/');
    define('APP_UPLOADS_URL', APP_RESOURCES_URL . 'uploads/');
    define('APP_TEMPLATE_URL', APP_RESOURCES_URL . 'template/' . $app_template . '/');
    define('APP_TEMPLATE_IMAGES_URL', APP_TEMPLATE_URL . 'img/');


    /**
     * BOWER
     */
    /**
     * BOWER - FOUNDATION 6.X
     */
    define('BOWER_PACKAGES_URL', APP_URL . 'bower_components/');
    define('COMPOSER_FOUNDATION_URL', BOWER_PACKAGES_URL . 'foundation-sites/');
    define('COMPOSER_FOUNDATION_CSS_URL', COMPOSER_FOUNDATION_URL . 'dist/css/foundation.min.css');
    define('COMPOSER_FOUNDATION_JS_URL', COMPOSER_FOUNDATION_URL . 'dist/js/foundation.min.js');

    /**
     * COMPOSER
     */
    define('COMPOSER_PACKAGES_URL', APP_URL . 'vendor/');
}
