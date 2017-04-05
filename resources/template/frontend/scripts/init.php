<?php

namespace k1app;

use k1lib\templates\temply as temply;
use \k1lib\html\script as script;

frontend::start(K1LIB_LANG);

$head = frontend::html()->head();
$body = frontend::html()->body();

$main_css = COMPOSER_FOUNDATION_CSS_URL;
\k1lib\crudlexs\input_helper::$main_css = $main_css;

/**
 * HTML HEAD
 */
$head->set_title(APP_TITLE);

$head->link_css(APP_TEMPLATE_URL . "css/normalize.min.css");
$head->link_css(APP_TEMPLATE_URL . "css/slick.css");
$head->link_css(APP_TEMPLATE_URL . "css/slick-theme.css");
$head->link_css(APP_TEMPLATE_URL . "css/main.css");
$head->link_css(BOWER_PACKAGES_URL . "jqueryui/themes/base/all.css");
$head->append_child(new script(BOWER_PACKAGES_URL . "tinymce/tinymce.min.js"));
$head->append_child(new script(APP_TEMPLATE_URL . "js/vendor/modernizr-2.8.3.min.js"));

$head->load_file(APP_TEMPLATE_PATH . 'sections/header-favicon.php', \k1lib\html\INSERT_ON_BEFORE_TAG_CLOSE);
$head->load_file(APP_TEMPLATE_PATH . 'sections/header-open-graph.php', \k1lib\html\INSERT_ON_BEFORE_TAG_CLOSE);

/**
 * HTML BODY
 */
$body->append_child_tail(new script(APP_TEMPLATE_URL . "js/jquery-3.1.1.min.js"));
$body->append_child_tail(new script(BOWER_PACKAGES_URL . "jqueryui/jquery-ui.min.js"));
$body->append_child_tail(new script(APP_TEMPLATE_URL . "js/plugins.js"));
$body->append_child_tail(new script(APP_TEMPLATE_URL . "js/slick.min.js"));
$body->append_child_tail(new script(APP_TEMPLATE_URL . "js/main.js?time=" . time()));
$body->load_file(APP_TEMPLATE_PATH . 'sections/facebook-pixel.php', \k1lib\html\INSERT_ON_BEFORE_TAG_CLOSE);
$body->load_file(APP_TEMPLATE_PATH . 'sections/google-analytics.php', \k1lib\html\INSERT_ON_BEFORE_TAG_CLOSE);

