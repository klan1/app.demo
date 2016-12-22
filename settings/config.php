<?php

namespace k1app;

if (!defined("\k1app\IN_K1APP")) {
    die("hacking attemp '^_^ " . __FILE__);
}

date_default_timezone_set("America/Bogota");

/*
 *  NAME AND DESCRIPTION
 */

const APP_TITLE = "Imepex Suite";
const APP_DESCRIPTION = "Suite de control de clientes y exportaciones.";
const APP_VERBOSE = 0;

/**
 * SET a CUSTOM K1MAGIC for K1.lib
 */
// # md5 -s "k1 app demo"
//MD5 ("k1 app demo") = ffb07e0d73382f34ffdd99567c39921c
\k1lib\K1MAGIC::set_value("ffb07j0d73382f34ffdd99567c39921c");

/**
 * URL REWRITE ENABLE
 */
\k1lib\urlrewrite\url::enable();

/**
 * TEMPLATE AND OUTPUT BUFFER SYSTEM ENABLE
 */
\k1lib\templates\temply::enable(\k1app\APP_MODE);

/*
 * SESSION CONFIG
 */
\k1lib\session\session_plain::enable();
\k1lib\session\session_plain::set_use_ip_in_userhash(FALSE);
\k1lib\session\session_plain::set_session_name("K1APP-IMEPEX-SUITE");
\k1lib\session\session_plain::set_app_user_levels([
    'god',
    'admin',
    'user',
    'guest'
]);

/**
 * SQL PROFILER ENABLE
 */
\k1lib\sql\profiler::enable();
/**
 * SQL LOCAL CACHE ENABLE
 */
\k1lib\sql\local_cache::enable();

/**
 * FILE UPLOADS ENABLE
 */
\k1lib\forms\file_uploads::enable(APP_UPLOADS_PATH, APP_UPLOADS_URL);
//\k1lib\forms\file_uploads::set_overwrite_existent(FALSE);
/**
 * TEMPLATE CONFIG
 */
\k1lib\html\template::enable(APP_TEMPLATE_PATH);
/*
 * DB CONFIG
 */
if ($_SERVER['SERVER_NAME'] != 'k1dev.local') {
    include "config-db-remote.php";
} else {
    \k1lib\db\handler::enable("k1app_imepex_suite", 'k1dev', '', "localhost", "3306", "mysql");
}

/**
 * DB Security
 */
include_once 'db-tables-aliases.php';
/**
 * Controllers Config
 */
include_once 'controllers-config.php';
/*
 * OTHERS
 */
//ini_set('memory_limit', '100M');

//ROUND numbers on all html foundation tables
\k1lib\html\foundation\table_from_data::$float_round_default = 1;
