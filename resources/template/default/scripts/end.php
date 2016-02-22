<?php

namespace k1app;

use \k1lib\templates\temply as temply;

/**
 * This file es called every time in the app
 */
if (temply::is_place_registered("app-title")) {
    if (temply::get_place_value("app-title") === false) {
        temply::set_place_value("app-title", APP_TITLE);
    }
}
if (temply::is_place_registered("html-title")) {
    if (temply::get_place_value("html-title") === false) {
        temply::set_place_value("html-title", APP_TITLE);
    }
}   