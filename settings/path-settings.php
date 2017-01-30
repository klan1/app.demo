<?php

namespace k1app;

// MAIN PATH
define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT']);

$server_name = $_SERVER['SERVER_NAME'];

/**
 * TEMPLATE NAME
 */
const APP_TEMPLATE = 'k1phphtml';

// AUTO CONFIGURATED PATHS
define('APP_ROOT', str_replace('\\', '/', dirname(dirname(__FILE__))));
define('APP_DIR', basename(APP_ROOT) . '/');
define('APP_DOMAIN', $server_name . (( $_SERVER['SERVER_PORT'] != '80') ? ':' . $_SERVER['SERVER_PORT'] : ''));


define('APP_CONTROLLERS_PATH', APP_ROOT . '/controllers/');

const APP_CONTROLLERS_PATH = \APP_CONTROLLERS_PATH;

define('APP_VIEWS_PATH', APP_ROOT . '/views/');

const APP_VIEWS_PATH = \APP_VIEWS_PATH;

define('APP_VIEWS_CRUD_PATH', APP_VIEWS_PATH . '/k1lib.crud/');
define('APP_RESOURCES_PATH', APP_ROOT . '/resources/');
define('APP_SETTINGS_PATH', APP_ROOT . '/settings/');
define('APP_UPLOADS_PATH', APP_RESOURCES_PATH . 'uploads/');
define('APP_SHELL_SCRIPTS_PATH', APP_RESOURCES_PATH . '/shell-scripts/');
define('APP_TEMPLATE_PATH', APP_RESOURCES_PATH . '/template/' . APP_TEMPLATE . '/');
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
if (\k1app\APP_MODE != 'shell') {

    /**
     * If this error is trigger you should set by hand the CONST: APP_BASE_URL
     * with your personal configuration.
     */
    if (isset($_SERVER['CONTEXT_PREFIX'])) {
        define('APP_BASE_URL', $_SERVER['CONTEXT_PREFIX'] . '/');
    } else {
        $app_base_url = dirname(substr($_SERVER['SCRIPT_FILENAME'], strlen(DOCUMENT_ROOT))) . '/';
        if ($app_base_url == '//') {
            $app_base_url = '/';
        }
        define('APP_BASE_URL', $app_base_url);
    }

    define('APP_DOMAIN_URL', (\k1lib\common\get_http_protocol() . '://') . \APP_DOMAIN);
    define('APP_URL', APP_DOMAIN_URL . APP_BASE_URL);
    define('APP_LOGIN_URL', APP_URL . 'log/form/');
    define('APP_HOME_URL', APP_URL);
    define('APP_CONTROLLERS_URL', APP_URL . 'controllers/');
    define('APP_VIEWS_URL', APP_URL . 'views/');
    define('APP_RESOURCES_URL', APP_URL . 'resources/');
    define('APP_UPLOADS_URL', APP_RESOURCES_URL . 'uploads/');
    define('APP_TEMPLATE_URL', APP_RESOURCES_URL . 'template/' . APP_TEMPLATE . '/');
    define('APP_TEMPLATE_IMAGES_URL', APP_TEMPLATE_URL . 'img/');

    /**
     * COMPOSER
     */
    define('COMPOSER_PACKAGES_URL', APP_URL . 'vendor/');
    /**
     * COMPOSER - FOUNDATION 6.X
     */
    define('COMPOSER_FOUNDATION_URL', COMPOSER_PACKAGES_URL . 'zurb/foundation/');
    define('COMPOSER_FOUNDATION_CSS_URL', COMPOSER_FOUNDATION_URL . 'dist/foundation.min.css');
    define('COMPOSER_FOUNDATION_JS_URL', COMPOSER_FOUNDATION_URL . 'dist/foundation.min.js');
    /**
     * BOWER
     */
    define('BOWER_PACKAGES_URL', APP_URL . 'bower_components/');
}
