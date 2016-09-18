<?php

namespace k1app;

use k1lib\templates\temply as temply;

/**
 * This file is called every time in the app
 */
temply::register_place("header");
temply::register_place("html-title");
temply::register_place("app-title");
temply::register_place("controller-name");
temply::register_place("footer");
temply::register_place("foote_app_infor");
temply::register_place("html-footer");

$main_css = COMPOSER_FOUNDATION_CSS_URL;

temply::register_header($main_css);
temply::register_header(BOWER_PACKAGES_URL . "foundation-icon-fonts/foundation-icons.css");
//temply::register_header(BOWER_PACKAGES_URL . "jqueryui/themes/base/all.css");
//temply::register_header(APP_RESOURCES_URL . "/html5/css/app.css");
temply::register_header(APP_RESOURCES_URL . "html5/css/k1-app.css");

temply::register_footer(BOWER_PACKAGES_URL . "jquery/dist/jquery.min.js");
//temply::register_footer(BOWER_PACKAGES_URL . "jqueryui/jquery-ui.min.js");
temply::register_footer(BOWER_PACKAGES_URL . "what-input/what-input.min.js");
temply::register_footer(BOWER_PACKAGES_URL . "tinymce/tinymce.min.js");
temply::register_footer(COMPOSER_FOUNDATION_JS_URL);
//temply::register_footer(APP_RESOURCES_URL . "/html5/js/app.js");
temply::register_footer(APP_RESOURCES_URL . "html5/js/k1app.js");

temply::set_place_value("html-title", APP_TITLE);

\k1lib\crudlexs\input_helper::$main_css = $main_css;
