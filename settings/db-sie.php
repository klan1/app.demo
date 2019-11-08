<?php

namespace k1app;

/*
 * DB OBJECT CONNECTION
 */
try {
    /**
     * @var \k1lib\db\PDO_k1 
     */
    $db_sie = new \k1lib\db\PDO_k1("k1app_sie2019", 'sie2017v1', 'DBaccess', "klan1.net", "3306", "mysql");
    $db_sie->set_verbose_level(APP_VERBOSE);
} catch (\PDOException $e) {
    trigger_error($e->getMessage(), E_USER_ERROR);
}
$db_sie->exec("set names utf8");
