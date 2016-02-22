<?php

namespace k1app;

use k1lib\session\session_plain as session_plain;

\k1lib\common\check_on_k1lib();

if (session_plain::is_logged()) {
    \k1lib\html\html_header_go("auto-app/");
} else {
    \k1lib\html\html_header_go(APP_URL . "log/form/");
}
