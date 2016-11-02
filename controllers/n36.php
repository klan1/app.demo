<?php

namespace k1app;

$url_value = \k1lib\urlrewrite\url::set_url_rewrite_var(1, "data");

$n36 = \k1lib\utils\decimal_to_n36($url_value);

d($n36);

d(\k1lib\utils\n36_to_decimal($n36));


