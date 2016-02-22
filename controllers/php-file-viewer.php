<?php

namespace k1app;

use \k1lib\urlrewrite\url_manager;

if (isset($_GET['file']) && isset($_GET['auth'])) {
    if ($_GET['auth'] == md5(APP_CONTROLLERS_PATH . $_GET['file'] . \k1lib\K1MAGIC::get_value())) {
        show_source($_GET['file']);
    } else {
        echo "Bad magic!";
    }
} else {
    echo "No file to show.";
}