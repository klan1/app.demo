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
$db_script =  'db.php';
require_once $db_script;

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

/**
 * PHP
 */
$php_file_template = <<<tpl
<?php

namespace k1app;

require '{$db_script}';

\$crud_api_obj = new api\\auth\\api_crud(TRUE,TRUE);
\$crud_api_obj->set_db(\$db);
\$crud_api_obj->set_db_table_name('@TABLE_NAME@');
\$crud_api_obj->set_db_table_keys_fields([@KEY_FIELDS@]);
\$crud_api_obj->exec();

tpl;

$models_path = APP_CONTROLLERS_PATH . 'api/tables/';

$db_tables = \k1lib\sql\sql_query($db, "show tables", TRUE, FALSE);
$ts_file_content = '';
$ts_classes_content = '';
foreach ($db_tables as $tabla_name_data) {
    $table_name = $tabla_name_data['Tables_in_' . $db->get_db_name()];
    $table_config = \k1lib\sql\get_db_table_config($db, $table_name, false);
    $field_keys = \k1lib\sql\get_db_table_keys_array($table_config);
    $php_file_content = str_replace('@TABLE_NAME@', $table_name, $php_file_template);
    if (!empty($field_keys)) {
        $php_file_content = str_replace('@KEY_FIELDS@', "'" . implode("','", $field_keys) . "'", $php_file_content);
    } else {
        $php_file_content = str_replace('@KEY_FIELDS@', '', $php_file_content);
    }
    $php_api_file_name = $models_path . str_replace('_', '-', $table_name) . '.php';
    echo $php_api_file_name . "\n";
    echo $php_file_content;
    file_put_contents($php_api_file_name, $php_file_content, LOCK_EX);
}
