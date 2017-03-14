<?php

namespace k1app;

// This might be different on your proyect

use k1lib\html\template as template;
use \k1lib\urlrewrite\url as url;
use k1lib\session\session_db as session_db;
use k1lib\notifications\on_frontend as frontend_notifications;

$body = frontend::html()->body();

template::load_template('html-head');
template::load_template('app-header');

$body->content()->set_class('home');
$body->content()->load_file(APP_TEMPLATE_PATH . 'html/home-slider.php');
$body->content()->load_file(APP_TEMPLATE_PATH . 'html/home-carusel.php');
$body->content()->load_file(APP_TEMPLATE_PATH . 'html/home-categories.php');

template::load_template('app-footer');

