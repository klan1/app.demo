<?php

namespace k1app;

use k1lib\html\template as template;

use k1app\k1app_template as DOM;

$body = DOM::html()->body();

template::load_template('header');
template::load_template('app-header');
template::load_template('app-footer');


$body->content()->set_value("Soon you will find useful information here...");


