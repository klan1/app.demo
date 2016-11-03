<?php

namespace k1app;

use k1lib\urlrewrite\url as url;
use \k1lib\session\session_db as k1lib_session;

if (k1lib_session::is_logged()) {
    \k1lib\html\html_header_go(url::do_url("app/"));
} else {
    \k1lib\html\html_header_go(url::do_url("log/form/"));
}
    