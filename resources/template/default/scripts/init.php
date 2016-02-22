<?php

namespace k1app;

use k1lib\templates\temply as temply;

/**
 * This file es called every time in the app
 */
temply::register_place("header");
temply::register_place("html-title");
temply::register_place("app-title");
temply::register_place("controller-name");
temply::register_place("footer");
temply::register_place("foote_app_infor");
temply::register_place("html-footer");

$main_css = APP_RESOURCES_URL . "/html5/css/foundation.css";

temply::register_header($main_css);
temply::register_header(APP_RESOURCES_URL . "/html5/css/foundation-icons/foundation-icons.css");
temply::register_header(APP_RESOURCES_URL . "/html5/jquery-ui-1.11.4.custom/jquery-ui.min.css");
temply::register_header(APP_RESOURCES_URL . "/html5/css/app.css");
temply::register_header(APP_RESOURCES_URL . "/html5/css/k1-app.css");
temply::register_header(APP_RESOURCES_URL . "/html5/js/tinymce/tinymce.min.js");

temply::register_footer(APP_RESOURCES_URL . "/html5/js/vendor/jquery.min.js");
temply::register_footer(APP_RESOURCES_URL . "/html5/jquery-ui-1.11.4.custom/jquery-ui.min.js");
temply::register_footer(APP_RESOURCES_URL . "/html5/js/vendor/what-input.min.js");
temply::register_footer(APP_RESOURCES_URL . "/html5/js/foundation.min.js");
temply::register_footer(APP_RESOURCES_URL . "/html5/js/app.js");
temply::register_footer(APP_RESOURCES_URL . "/html5/js/k1app.js");

temply::set_place_value("html-title", APP_TITLE);

\k1lib\crudlexs\input_helper::$main_css = $main_css;

/**
<!DOCTYPE html>
<html>
<head>

  <title>jQuery Mobile page</title>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="css/themes/my-custom-theme.css" />
  <link rel="stylesheet" href="css/themes/jquery.mobile.icons.min.css" />
  <link rel="stylesheet" href="http://code.jquery.com/mobile/1.4.5/jquery.mobile.structure-1.4.5.min.css" /> 
  <script src="http://code.jquery.com/jquery-1.11.1.min.js"></script> 
  <script src="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script> 

</head>
 */