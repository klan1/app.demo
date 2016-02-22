<?php

namespace k1app;

use k1lib\urlrewrite\url_manager as url_manager;

// This will work because the URL internal index is from 0
$next_url_level = url_manager::get_url_level_count();
// get the base URL to load the next one
$actual_url = url_manager::get_this_url(FALSE);
// get from the URL the next level value :   /$actual_url/next_level_value
$next_directory_name = url_manager::set_url_rewrite_var($next_url_level, "next_directory_name", TRUE);

$file_to_include = \k1lib\controllers\load_controller($next_directory_name, \k1app\APP_CONTROLLERS_PATH . $actual_url);
include $file_to_include;
if (\k1lib\templates\temply::is_place_registered("php-file-to-show")) {
    \k1lib\templates\temply::set_place_value("php-file-to-show", str_replace(APP_CONTROLLERS_PATH, "", $file_to_include) . "&auth=" . md5($file_to_include . \k1lib\K1MAGIC::get_value()));
}
unset($next_url_level);
unset($actual_url);
unset($next_directory_name);
//unset($file_to_include);
