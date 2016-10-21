<?php

namespace k1app;

use \k1lib\templates\temply as temply;
use \k1lib\urlrewrite\url as url;
use \k1lib\html\DOM as DOM;
use k1lib\session\session_db as session_db;

if (!isset($app_session)) {
    $app_session = new session_db($db);
}

$body = DOM::html()->body();

include temply::load_template("header", APP_TEMPLATE_PATH);

// Form behaivor values
$form_magic_value = \k1lib\common\set_magic_value("login_form");
$form_action = \k1lib\urlrewrite\url::do_url("in");

$form_values = \k1lib\common\unserialize_var("login");

$body->set_id("login-form-body");

$form = new \k1lib\html\form("login-form-object");
$form->append_to($body->content());
$form->set_attrib("action", url::do_url(APP_URL . "log/in/"));
$form->append_child(new \k1lib\html\input("hidden", "magic_value", $form_magic_value));

$content_grid = new \k1lib\html\foundation\grid_row(1, 1, $form);
$content_grid->col(1)->medium(8)->medium_centered()->large(5)->large_centered();

$main_grid = $content_grid->col(1)->append_grid(4, 1);
$main_grid->row(1)->col(1)->set_class("text-center")->set_id("k1lib-login-logo")->append_child(new \k1lib\html\img(APP_TEMPLATE_IMAGES_URL . "klan1.png"));
$main_grid->row(2)->col(1)->set_id("k1lib-login-title")->set_class("text-left")->append_h1(APP_TITLE);
$klan1_link = new \k1lib\html\a("http://www.klan1.com?ref=k1.app", "Klan1 Network", "_blank", NULL, "klan1-site-link");

$login_grid = $main_grid->row(3)->col(1)->append_grid(6, 1)->set_id("k1app-login-content");

$grid_row_login = (new \k1lib\html\foundation\label_value_row("Login", (new \k1lib\html\input("text", "login", NULL))))->set_attrib("placeholder", "Login")->append_to($login_grid->row(2)->col(1));
$grid_row_password = (new \k1lib\html\foundation\label_value_row("Password", (new \k1lib\html\input("password", "pass", NULL))))->set_attrib("placeholder", "Password")->append_to($login_grid->row(3)->col(1));
$grid_row_remember = (new \k1lib\html\foundation\label_value_row("Remember me", (new \k1lib\html\input("checkbox", "remember-me", NULL))))->append_to($login_grid->row(4)->col(1));

$button_grid = $login_grid->row(5)->col(1)->append_row(2);
$button_grid->col(1)->small(6)->append_a("javascript:alert('Sorry to hear that!, Please contacta an administrator')", "Forgot your password?");
$button_grid->col(2)->small(6)->set_class("text-right")->append_child(new \k1lib\html\input("submit", NULL, "Login", "button"));

$main_grid->row(4)->col(1)->append_h6("© 2013-2016 Developed by $klan1_link")->set_id("k1lib-login-copyright");


