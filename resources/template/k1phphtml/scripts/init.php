<?php

namespace k1app;

use k1lib\templates\temply as temply;
use k1app\k1app_template as DOM;
use \k1lib\html\script as script;

DOM::start(K1LIB_LANG);

$head = DOM::html()->head();
$body = DOM::html()->body();

$main_css = COMPOSER_FOUNDATION_CSS_URL;
\k1lib\crudlexs\input_helper::$main_css = $main_css;

/**
 * HTML HEAD
 */
$head->set_title(K1APP_TITLE);

$head->link_css(COMPOSER_FOUNDATION_CSS_URL);
$head->link_css(BOWER_PACKAGES_URL . "foundation-icon-fonts/foundation-icons.css");
$head->link_css(APP_TEMPLATE_URL . "css/responsive-tables.css");
$head->link_css(BOWER_PACKAGES_URL . "foundation-datepicker/css/foundation-datepicker.css");
$head->link_css(APP_TEMPLATE_URL . "css/k1-app.css?time=" . time());
$head->link_css(APP_TEMPLATE_URL . "css/custom-styles.css?time=" . time());

$head->append_child_tail(new script(BOWER_PACKAGES_URL . "jquery/dist/jquery.min.js"));
$head->append_child_tail(new script(BOWER_PACKAGES_URL . "tinymce/tinymce.min.js"));
$head->append_child_tail(new script(BOWER_PACKAGES_URL . "foundation-datepicker/js/foundation-datepicker.min.js"));
if (K1LIB_LANG != 'en') {
    $head->append_child_tail(new script(BOWER_PACKAGES_URL . "foundation-datepicker/js/locales/foundation-datepicker." . K1LIB_LANG . ".js"));
}

/**
 * HTML BODY
 */
$body->append_child_tail(new script(BOWER_PACKAGES_URL . "what-input/dist/what-input.min.js"));
$body->append_child_tail(new script(COMPOSER_FOUNDATION_JS_URL));
$body->append_child_tail(new script(APP_TEMPLATE_URL . "js/k1app.js?time=" . time()));
$body->append_child_tail(new script(APP_TEMPLATE_URL . "js/custom-scripts.js?time=" . time()));

