<?php

namespace k1app;

use \k1lib\urlrewrite\url as url;

\k1lib\common\check_on_k1lib();

$controller_to_load = url::set_next_url_level(APP_CONTROLLERS_PATH, FALSE);

if (!$controller_to_load) {
    \k1lib\html\html_header_go(url::do_url("./form"));
} else {
    require $controller_to_load;
}