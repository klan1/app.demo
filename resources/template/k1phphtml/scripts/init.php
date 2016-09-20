<?php

namespace k1app;

use k1lib\templates\temply as temply;
use \k1lib\html\DOM as DOM;
use \k1lib\html\script_tag as script_tag;

$head = DOM::html()->head();
$body = DOM::html()->body();

$main_css = COMPOSER_FOUNDATION_CSS_URL;
\k1lib\crudlexs\input_helper::$main_css = $main_css;

/**
 * HTML HEAD
 */
$head->set_title(APP_TITLE);

$head->link_css(COMPOSER_FOUNDATION_CSS_URL);
$head->link_css(BOWER_PACKAGES_URL . "foundation-icon-fonts/foundation-icons.css");
$head->link_css(BOWER_PACKAGES_URL . "jqueryui/themes/base/all.css");
$head->link_css(APP_RESOURCES_URL . "html5/css/k1-app.css");
$head->append_child(new script_tag(BOWER_PACKAGES_URL . "tinymce/tinymce.min.js"));

/**
 * HTML BODY
 */
$body->append_child_tail(new script_tag($main_css));
$body->append_child_tail(new script_tag(BOWER_PACKAGES_URL . "jquery/dist/jquery.min.js"));
$body->append_child_tail(new script_tag(BOWER_PACKAGES_URL . "jqueryui/jquery-ui.min.js"));
$body->append_child_tail(new script_tag(BOWER_PACKAGES_URL . "what-input/what-input.min.js"));
$body->append_child_tail(new script_tag(COMPOSER_FOUNDATION_JS_URL));
$body->append_child_tail(new script_tag(APP_RESOURCES_URL . "html5/js/k1app.js"));

$body->init_sections();
$body->content()->set_class("k1-main-section");

/**
 * This file is called every time in the app
 * TODO: DO not use this any more
 */
temply::register_place("header");
temply::register_place("html-title");
temply::register_place("app-title");
temply::register_place("controller-name");
temply::register_place("footer");
temply::register_place("foote_app_infor");
temply::register_place("html-footer");

temply::set_place_value("app-title", APP_TITLE);
