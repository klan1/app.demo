<?php

namespace k1app;

use k1lib\html\template as template;
use \k1lib\urlrewrite\url as url;
use k1app\k1app_template as DOM;

$body = DOM::html()->body();

template::load_template('header');
template::load_template('app-header');
template::load_template('app-footer');

DOM::html()->head()->set_title(APP_TITLE . " | Admin Dashboard");
DOM::set_title(3, 'Admin Dashboard');

DOM::menu_left()->set_active('nav-dashboard');

