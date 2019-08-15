<?php
$full_script_name = __DIR__ . $_SERVER['REQUEST_URI'];
$full_script_name = substr($full_script_name, 0, (strpos($full_script_name,'?') !== FALSE ? strpos($full_script_name,'?') : strlen($full_script_name)));
if (is_file($full_script_name) && (strstr($_SERVER['SCRIPT_NAME'], 'index.php') === FALSE)) {
    return false;
} elseif (
        (strstr($_SERVER['REQUEST_URI'], 'time=') !== FALSE) ||
        preg_match('/\.(?:js|xml|htm|html|css|jpg|png|svg|gif|htc|ico|zip|rar|pdf|mp3|swf|map|php|woff|ttf)$/', $_SERVER["REQUEST_URI"])
) {
//    print "B";
    return false;
} else {
    $_GET['K1LIB_URL'] = (strlen($_SERVER['REQUEST_URI']) > 0) ? substr($_SERVER['REQUEST_URI'], 1) : NULL;
    include __DIR__ . '/index.php';
}