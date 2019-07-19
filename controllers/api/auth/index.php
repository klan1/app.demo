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
