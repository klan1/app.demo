<?php

namespace k1app;

use \k1lib\urlrewrite\url as url;

\k1lib\common\check_on_k1lib();

require "dirmgnt-optional.php";

if (!$dirmgnt_include_sucess) {
    \k1lib\html\html_header_go(url::do_url("./form"));
}