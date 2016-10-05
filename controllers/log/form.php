<?php

namespace k1app;

use \k1lib\templates\temply as temply;
use \k1lib\urlrewrite\url as url;
use \k1lib\html\DOM as DOM;

$body = DOM::html()->body();

include temply::load_template("header", APP_TEMPLATE_PATH);
include temply::load_template("app-header", APP_TEMPLATE_PATH);
include temply::load_template("app-footer", APP_TEMPLATE_PATH);

// Form behaivor values
$form_magic_value = \k1lib\common\set_magic_value("login_form");
$form_action = \k1lib\urlrewrite\url::do_url("in");

$form_values = \k1lib\common\unserialize_var("login");

switch ($app_error) {
    case "not-logged":
        $app_messajes = \k1lib\common\get_error("Debes ingresar primero a la aplicacion");
        break;
    case "bad-login":
        $app_messajes = \k1lib\common\get_error("Usuario y contraseña erroneos");
        break;
    case "bad-magic":
        $app_messajes = \k1lib\common\get_error("Very BAD Magic!!");
        break;
    case "no-data":
        $app_messajes = \k1lib\common\get_error("No se han recibido datos");
        break;
    default:
        $app_messajes = NULL;
        break;
}


include temply::load_template("html-parts/login", APP_TEMPLATE_PATH);
