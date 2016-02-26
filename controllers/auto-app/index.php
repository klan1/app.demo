<?php

namespace k1app;

use \k1lib\urlrewrite\url as url;

\k1lib\session\session_plain::is_logged(TRUE, url::do_url(APP_URL . "log/form/", ["back-url" => $_SERVER['REQUEST_URI']]));

if (\k1lib\session\session_plain::check_user_level(["god", 'admin', 'user'])) {
    require 'dirmgnt-optional.php';

    if (!$dirmgnt_include_sucess) {
        $go_url = APP_URL . \k1lib\urlrewrite\url::make_url_from_rewrite() . "show-tables/";
        \k1lib\html\html_header_go($go_url);
    }
} else {
    d("You can't thouch this... can't touch this... ta la la la...");
}
