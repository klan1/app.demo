<?php

namespace k1app;

// This might be different on your proyect

use k1lib\html\template as template;
use \k1lib\urlrewrite\url as url;
use k1lib\session\session_db as session_db;
use k1lib\notifications\on_frontend as frontend_notifications;

$body = frontend::html()->body();

$body->set_class('home-page');

template::load_template('html-head');
template::load_template('app-header');

$body->content()->set_class('step2');
$body->content()->load_file(APP_TEMPLATE_PATH . 'sections/step2-content.php');

template::load_template('app-footer');

