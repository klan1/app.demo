<?php

/*
 * Easy and clean start, not bad, learned from WordPress :D
 */

namespace k1app;

// Composer lines
define("K1LIB_LANG", "es");
require __DIR__ . '/vendor/autoload.php';

// k1.app start
const APP_MODE = "web";
include_once "./bootstrap.php";
