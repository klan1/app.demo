<?php

namespace k1app;

// TODO: Fix this
$controller_to_load = \k1lib\urlrewrite\url::set_next_url_level(APP_CONTROLLERS_PATH, TRUE);

if (!$controller_to_load) {
    http_response_code(404);
    echo var_dump($controller_to_load);
} else {
    require $controller_to_load;
}

//use \k1lib\urlrewrite\url;
//
//url::set_api_mode();
//
//$actual_url = url::get_this_url();
//
//$possible_id = url::set_url_rewrite_var(url::get_url_level_count(), 'possible-id', TRUE);
//$possible_action = url::set_url_rewrite_var(url::get_url_level_count(), 'possible-action', FALSE);
//
//// IF THERE IS ONLY $possible_id
//if (!empty($possible_id)) {
//    if (empty($possible_action)) {
//        $next_directory_name = $possible_id;
//    } else {
//        $K1APP_URL_VALUE = $possible_id;
//        $next_directory_name = $possible_action;
//    }
//}
//
//$file_to_include = \k1lib\controllers\load_controller($next_directory_name, APP_CONTROLLERS_PATH . $actual_url, FALSE, url::get_api_mode());
//require $file_to_include;
