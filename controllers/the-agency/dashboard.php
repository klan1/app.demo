<?php

namespace k1app;

use k1lib\templates\temply as temply;

use \k1lib\html\DOM as DOM;

$body = DOM::html()->body();

template::load_template('header');
template::load_template('app-header');
template::load_template('app-footer');


$body->content()->set_value("Soon you will find useful information here...");


