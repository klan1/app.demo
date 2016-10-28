<?php

namespace k1app;

use k1lib\templates\temply as temply;

use \k1lib\html\DOM as DOM;

$body = DOM::html()->body();

include temply::load_template("header", APP_TEMPLATE_PATH);
include temply::load_template("app-header", APP_TEMPLATE_PATH);
include temply::load_template("app-footer", APP_TEMPLATE_PATH);


$body->content()->set_value("Soon you will find useful information here...");

