<?php

namespace k1app;

\k1lib\common\check_on_k1lib();

require "dirmgnt-optional.php";

if (!$dirmgnt_include_sucess) {
    \k1lib\html\html_header_go("./form");
}