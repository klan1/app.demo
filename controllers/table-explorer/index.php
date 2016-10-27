<?php

namespace k1app;

use \k1lib\urlrewrite\url as url;

//\k1lib\session\session_db::is_logged(TRUE, url::do_url(APP_URL . "log/form/", ["back-url" => $_SERVER['REQUEST_URI']]));

if (\k1lib\session\session_db::check_user_level(crudlexs_config::CONTROLLER_ALLOWED_LEVELS)) {
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
