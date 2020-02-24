<?php

namespace k1app;

$ping = new api\utils\ping_api();
$ping->do_debug();
$ping->exec();
