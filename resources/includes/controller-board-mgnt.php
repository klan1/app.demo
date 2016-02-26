<?php

namespace k1app;

use k1lib\urlrewrite\url as url;

// This will work because the URL internal index is from 0
$this_url_level_value = url::get_url_level_value();
$next_url_level = url::get_url_level_count();
// get the base URL to load the next one
$actual_url = url::make_url_from_rewrite(-1);
// get from the URL the next level value :   /$actual_url/next_level_value
$controller_board_name = url::set_url_rewrite_var($next_url_level, "controller_board_name", FALSE);

$controller_to_load = $this_url_level_value . '-' . $controller_board_name;

if ($controller_board_name !== FALSE) {
    $controller_to_load = $this_url_level_value . '-' . $controller_board_name;
    $file_to_include = \k1lib\controllers\load_controller($controller_to_load, \k1app\APP_CONTROLLERS_PATH . $actual_url);
} else {
    $controller_to_load = $this_url_level_value . '-main';
    $file_to_include = \k1lib\controllers\load_controller($controller_to_load, \k1app\APP_CONTROLLERS_PATH . $actual_url);
}
include $file_to_include;

if (\k1lib\templates\temply::is_place_registered("php-file-to-show")) {
        \k1lib\templates\temply::set_place_value("php-file-to-show", str_replace(APP_CONTROLLERS_PATH, "", $file_to_include) . "&auth=" . md5($file_to_include . \k1lib\K1MAGIC::get_value()));
}
unset($this_url_level_value);
unset($next_url_level);
unset($actual_url);
unset($controller_board_name);
unset($controller_to_load);
//unset($file_to_include);
