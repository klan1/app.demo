<?php

namespace k1app;

/*
 * DB OBJECT CONNECTION
 */
try {
    /**
     * @var \k1lib\db\PDO_k1 
     */
    $db = new \k1lib\db\PDO_k1("k1app_sie_ch", 'k1appch', 'DBaccess', "klan1.net", "3306", "mysql");
    $db->set_verbose_level(K1APP_VERBOSE);
} catch (\PDOException $e) {
    trigger_error($e->getMessage(), E_USER_ERROR);
}
$db->exec("set names utf8");
