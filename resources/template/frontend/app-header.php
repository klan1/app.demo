<?php

namespace k1app;

use \k1lib\session\session_db as k1lib_session;
use \k1lib\urlrewrite\url as url;

$body = frontend::html()->body();

$body->header()->append_div(null, 'k1app-output');

$body->header()->load_file(APP_TEMPLATE_PATH . 'sections/body-header.php');

