<?php

namespace k1app;

// Composer lines
const K1LIB_LANG = "en";
const APP_MODE = "shell";
const IN_K1APP = TRUE;

require_once '../../vendor/autoload.php';
require_once '../../settings/path-settings.php';
require_once '../../settings/config.php';
/*
 * USE HERE THE DB CONFIG SCRIPT FILE
 */
require_once 'db.php';

/**
 * AUTOLOAD FOR APP CLASES
 */
spl_autoload_register(function($className) {
    $className = str_replace("\\", DIRECTORY_SEPARATOR, $className);
    $file_to_load = APP_CLASSES_PATH . $className . '.php';
    include_once $file_to_load;
});

/**
 * CLI Params
 */
if (isset($argv)) {
    
}

$ts_types = array(
    'char' => 'string',
    'varchar' => 'string',
    'text' => 'string',
    'date' => 'string',
    'time' => 'string',
    'datetime' => 'string',
    'timestamp' => 'number',
    'tinyint' => 'number',
    'smallint' => 'number',
    'mediumint' => 'number',
    'int' => 'number',
    'bigint' => 'number',
    'decimal' => 'number',
    'float' => 'number',
    'double' => 'number',
    'enum' => 'string',
    'set' => 'number',
);

$php_types = array(
    'char' => 'string',
    'varchar' => 'string',
    'text' => 'string',
    'date' => 'string',
    'time' => 'string',
    'datetime' => 'string',
    'timestamp' => 'int',
    'tinyint' => 'int',
    'smallint' => 'int',
    'mediumint' => 'int',
    'int' => 'int',
    'bigint' => 'int',
    'decimal' => 'float',
    'float' => 'float',
    'double' => 'float',
    'enum' => 'string',
    'set' => 'int',
);
/**
 * PHP
 */
$php_file_template = <<<tpl
<?php

namespace k1app\models;

use k1lib\api\api_model;

class @TABLE_NAME@_model extends api_model {
@VARS@
}       
tpl;
$php_var_template = <<<tpl
    /**
     * @var @TYPE@
     */
    public $@VARNAME@;\n
tpl;
/**
 * TypeSript
 */
$ts_file_template = <<<tpl
import { Injectable } from '@angular/core';

@Injectable()
@CLASSES@
tpl;
$ts_class_template = <<<tpl
export class @TABLE_NAME@Model {
@VARS@
}\n
tpl;
$ts_var_template = <<<tpl
    @VARNAME@: @TYPE@ = null;\n
tpl;

$models_path = APP_CLASSES_PATH . 'k1app/models/';
$ts_paths = [
    APP_ROOT . '/ts-output/db-models.ts'
];
//$ts_paths = [
//    '/Users/j0hnd03/Documents/AndroidStudioProjects/sip-soy-social-ionic-4/src/app/models/sip-db-models.ts',
//    '/Users/j0hnd03/Documents/AndroidStudioProjects/sip-yo-participo-ionic-4/src/app/models/sip-db-models.ts',
//    '/Users/j0hnd03/Documents/AndroidStudioProjects/sip-yo-participo-ionic-4/src/app/models/sip-db-models.ts',
//];

$db_tables = \k1lib\sql\sql_query($db, "show tables", TRUE, FALSE);

$ts_file_content = '';
$ts_classes_content = '';
foreach ($db_tables as $tabla_name_data) {
    echo "Table: " . $tabla_name_data . "\n";
    $table_name = $tabla_name_data['Tables_in_' . $db->get_db_name()];
    $table_config = \k1lib\sql\get_db_table_config($db, $table_name, false);
    /**
     * PHP
     */
    $php_var_code = '';
    $php_vars_code = '';
    $php_model_file_name = '';
    /**
     * typescript
     */
    $ts_var_code = '';
    $ts_vars_code = '';
    $ts_class_content = '';
//    $ts_class_list = [];
    foreach ($table_config as $field => $config) {
        /**
         * PHP
         */
        $php_var_code = str_replace('@TYPE@', $php_types[$config['type']], $php_var_template);
        $php_var_code = str_replace('@VARNAME@', $field, $php_var_code);
        $php_vars_code .= $php_var_code;
        /**
         * typescript
         */
        $ts_var_code = str_replace('@TYPE@', $ts_types[$config['type']], $ts_var_template);
        $ts_var_code = str_replace('@VARNAME@', $field, $ts_var_code);
        $ts_vars_code .= $ts_var_code;
    }
    /**
     * PHP
     */
    $php_file_content = str_replace('@TABLE_NAME@', $table_name, $php_file_template);
    $php_file_content = str_replace('@VARS@', $php_vars_code, $php_file_content);
    $php_model_file_name = $models_path . $table_name . '_model.php';
    /**
     * TYPESCRIPT
     */
    $ts_class_name = str_replace('_', ' ', $table_name);
    $ts_class_name = ucwords($ts_class_name);
    $ts_class_name = str_replace(' ', '', $ts_class_name);
    $ts_class_list[] = $ts_class_name . 'Model';

    $ts_class_content = str_replace('@TABLE_NAME@', $ts_class_name, $ts_class_template);
    $ts_class_content = str_replace('@VARS@', $ts_vars_code, $ts_class_content);
    $ts_classes_content .= $ts_class_content;
    echo $php_model_file_name . "\n";
//    print_r($table_config);
    echo $php_file_content;
    file_put_contents($php_model_file_name, $php_file_content, LOCK_EX);
//    exit;
}
$ts_file_content = str_replace('@CLASSES@', $ts_classes_content, $ts_file_template);
foreach ($ts_paths as $ts_path) {
    file_put_contents($ts_path, $ts_file_content, LOCK_EX);
}
echo "\nTS class list: " . implode(',', $ts_class_list) . "\n";



