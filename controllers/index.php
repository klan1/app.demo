<?php

namespace k1app;

use k1lib\session\session_db as session_db;
use k1lib\urlrewrite\url as url;

\k1lib\common\check_on_k1lib();

if (session_db::is_logged()) {
    \k1lib\html\html_header_go(url::do_url("table-explorer/"));
} else {
    \k1lib\html\html_header_go(url::do_url(APP_URL . "log/form/"));
}
