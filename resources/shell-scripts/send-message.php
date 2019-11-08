<?php

namespace k1app;

$send_start = microtime(true);

// Composer lines
define("K1LIB_LANG", "en");
require '../../vendor/autoload.php';

// k1.app start
define("APP_MODE", "shell");
include_once "../../bootstrap.php";

d("hello world!");
