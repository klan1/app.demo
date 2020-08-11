<?php

namespace k1app;

use k1lib\urlrewrite\url as url;
use k1lib\html\template as template;
use k1lib\session\session_db as session_db;

//include 'db.php';
include 'db.php';

/*
 * APP START
 */
session_db::set_session_name(SESSION_APP_NAME);
$app_session = new session_db($db);
$app_session->start_session();
$app_session->load_logged_session_db();

// Template init
template::load_template('scripts/init');

k1app_template::start_template();

$mysql_default_validation = array(
    'char' => 'mixed-symbols',
    'varchar' => 'mixed-symbols',
    'text' => 'mixed-symbols',
    'date' => 'date',
    'time' => 'time',
    'datetime' => 'datetime',
    'timestamp' => 'numbers',
    'tinyint' => 'numbers',
    'smallint' => 'numbers',
    'mediumint' => 'numbers',
    'int' => 'numbers',
    'bigint' => 'numbers',
    'float' => 'decimals',
    'double' => 'numbers',
    'enum' => 'options',
    'set' => 'options',
);
$k1lib_field_config_options_defaults = [
    'label' => null,
    'alias' => null,
    'validation' => null,
    'placeholder' => null,
    'show-create' => TRUE,
    'show-read' => TRUE,
    'show-update' => TRUE,
    'show-list' => TRUE,
    'show-related' => TRUE,
    'show-search' => TRUE,
    'show-export' => TRUE,
    'label-field' => null,
    'file-max-size' => null,
    'file-type' => null,
    'min' => 1,
    'sql' => null,
];

\k1lib\session\session_db::is_logged(TRUE, url::do_url(APP_URL . "app/log/form/", ["back-url" => $_SERVER['REQUEST_URI']]));

if (\k1lib\session\session_db::check_user_level(["god"])) {
    $controller_to_load = url::set_next_url_level(APP_CONTROLLERS_PATH, FALSE);

    if (!$controller_to_load) {
        $go_url = APP_URL . \k1lib\urlrewrite\url::make_url_from_rewrite() . "show-tables/";
        \k1lib\html\html_header_go($go_url);
    } else {
        require $controller_to_load;
    }
} else {
    d("You can't thouch this... can't touch this... ta la la la...");
}

// APP Debug output
template::load_template('verbose-output');
// Template end
template::load_template('scripts/end');
